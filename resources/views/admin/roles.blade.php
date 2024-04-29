<x-admin.layout>
    <x-slot name="title">Roles</x-slot>
    <x-slot name="heading">Roles</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <!-- Add Form Start -->
    <div class="row" id="addContainer" style="display:none;">
        <div class="col-sm-12">
            <div class="card">
                <form class="theme-form" name="addForm" id="addForm">
                    @csrf
                    <header class="card-header pb-0">
                        <h4 class="card-title">Add Role</h4>
                    </header>
                    <div class="card-body pt-0">

                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <label class="col-form-label" for="name">Role Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="name" name="name" type="text" placeholder="Enter Role Name">
                                <span class="text-danger is-invalid name_err"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            @php
                                $previousPermission = '';
                            @endphp

                            @foreach ($permissions as $permission)
                                @if ( $previousPermission != explode('.', $permission->name)[0] )
                                    </div>
                                    <div class="form-group m-t-15 row roles-checkbox-group">
                                        <strong class="mt-2"> {{ ucfirst( explode('.', $permission->name)[0] ) }} </strong>
                                        <div class="col-3 py-2 form-check">
                                            <label class="d-block form-check-label" for="chk-ani{{$permission->name}}">
                                                <input class="checkbox_animated form-check-input" id="chk-ani{{$permission->name}}" type="checkbox"  name="permission[]" value="{{$permission->name}}" checked>{{explode('.', $permission->name)[1]}}
                                            </label>
                                        </div>
                                @else
                                    <div class="col-3 py-2 form-check">
                                        <label class="d-block form-check-label" for="chk-ani{{$permission->name}}">
                                            <input class="checkbox_animated form-check-input" id="chk-ani{{$permission->name}}" type="checkbox"  name="permission[]" value="{{$permission->name}}" checked>{{explode('.', $permission->name)[1]}}
                                        </label>
                                    </div>
                                @endif
                                @php
                                    $previousPermission = explode('.', $permission->name)[0]
                                @endphp
                            @endforeach
                            <span class="text-danger is-invalid permission_err"></span>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Edit Form --}}
    <div class="row" id="editContainer" style="display:none;">
        <div class="col">
            <form class="form-horizontal form-bordered" method="post" id="editForm">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h4 class="card-title">Edit Role</h4>
                    </header>

                    <div class="card-body py-2">

                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <label class="col-form-label" for="edit_name">Role Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="edit_name" name="edit_name" type="text" placeholder="Enter Role Name">
                                <span class="text-danger is-invalid edit_name_err"></span>
                            </div>
                        </div>

                        <div class="mb-3 row px-2" id="edit_permission">
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" id="editSubmit">Update</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </section>
            </form>
        </div>
    </div>




    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="">
                                <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <button class="edit-element btn text-secondary px-2 py-1" title="Edit Role" data-id="{{ $role->id }}"><i data-feather="edit"></i></button>
                                            <button class="btn text-danger rem-element px-2 py-1" title="Delete Role" data-id="{{ $role->id }}"><i data-feather="trash-2"></i> </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-admin.layout>



{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('roles.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('roles.index') }}';
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#addSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#addSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

    });
</script>


<!-- Edit -->
<script>
    $("#buttons-datatables").on("click", ".edit-element", function(e) {
        e.preventDefault();
        // $(".edit-element").show();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('roles.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();

                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.role.id);
                    $("#editForm input[name='edit_name']").val(data.role.name);
                    $("#edit_permission").html(data.permissionsHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


<!-- Update -->
<script>
    $(document).ready(function() {
        $("#editForm").submit(function(e) {
            e.preventDefault();
            $("#editSubmit").prop('disabled', true);
            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');
            var model_id = $('#edit_model_id').val();
            var url = "{{ route('roles.update', ':model_id') }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#editSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                        .then((action) => {
                            window.location.href = '{{ route('roles.index') }}';
                        });
                    else
                        swal("Error!", data.error2, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        $("#editSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#editSubmit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                }
            });

        });
    });
</script>


<!-- Delete -->
<script>
    $("#buttons-datatables").on("click", ".rem-element", function(e) {
        e.preventDefault();
        swal({
            title: "Are you sure to delete this role?",
            // text: "Make sure if you have filled Vendor details before proceeding further",
            icon: "info",
            buttons: ["Cancel", "Confirm"]
        })
        .then((justTransfer) =>
        {
            if (justTransfer)
            {
                var model_id = $(this).attr("data-id");
                var url = "{{ route('roles.destroy', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'POST',
                    data: {
                        '_method': "DELETE",
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (!data.error && !data.error2) {
                            swal("Success!", data.success, "success")
                                .then((action) => {
                                    window.location.reload();
                                });
                        } else {
                            if (data.error) {
                                swal("Error!", data.error, "error");
                            } else {
                                swal("Error!", data.error2, "error");
                            }
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal("Error!", "Something went wrong", "error");
                    },
                });
            }
        });
    });
</script>
