<x-admin.layout>
    <x-slot name="title">Programme Audit List</x-slot>
    <x-slot name="heading">Programme Audit List</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <!-- Add Form -->
    <div class="row" id="addContainer" style="display:none;">
        <div class="col-sm-12">
            <div class="card">
                <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                    @csrf

                    <div class="card-header">
                        <h4 class="card-title">Add Programme Audit</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="department_id">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach ($departments->where('is_audit', 0) as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid department_id_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                <input class="form-control" name="date" type="date" onclick="this.showPicker()" placeholder="Select Date">
                                <span class="text-danger is-invalid date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="file">File Upload<span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <span class="text-danger is-invalid file_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" ></textarea>
                                <span class="text-danger is-invalid description_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="remark">Remark <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="remark" ></textarea>
                                <span class="text-danger is-invalid remark_err"></span>
                            </div>

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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Programme Audit</h4>
                    </div>
                    <div class="card-body py-2">
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">
                        <div class="mb-3 row">

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="department_id">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach ($departments->where('is_audit', 0) as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid department_id_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                <input class="form-control" name="date" type="date" onclick="this.showPicker()" placeholder="Select Date">
                                <span class="text-danger is-invalid date_err"></span>
                            </div>
                            <div class="col-md-1 mt-3">
                                <div class="edit_file pt-3 mt-3"></div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label class="col-form-label" for="file">File Upload<span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <span class="text-danger is-invalid file_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" ></textarea>
                                <span class="text-danger is-invalid description_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="remark">Remark <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="remark" ></textarea>
                                <span class="text-danger is-invalid remark_err"></span>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" id="editSubmit">Submit</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </div>
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
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>File Description</th>
                                    <th>Remark</th>
                                    <th>View File</th>
                                    <th>Status</th>
                                    <th>Reject Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $audit->department?->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->date)->format('d m Y') }}</td>
                                        <td>{{ Str::limit($audit->description, '85') }}</td>
                                        <td>{{ Str::limit($audit->remark, '85') }}</td>
                                        <td>
                                            <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $audit->status_name }}</span>
                                        </td>
                                        <td>
                                            @if ($audit->status == 3)
                                                <p>{{ Str::limit($audit->reject_reason, 85) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if($audit->status == 3 || $audit->status == 1)
                                                <button class="btn btn-secondary edit-element px-2 py-1" title="Edit audit" data-id="{{ $audit->id }}"><i data-feather="edit"></i></button>
                                            @endif
                                            @if($audit->status == 1)
                                                <button class="btn btn-danger rem-element px-2 py-1" title="Delete audit" data-id="{{ $audit->id }}"><i data-feather="trash-2"></i> </button>
                                            @endif
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


    @push('scripts')
        {{-- Add --}}
        <script>
            $("#addForm").submit(function(e) {
                e.preventDefault();
                $("#addSubmit").prop('disabled', true);

                var formdata = new FormData(this);
                $.ajax({
                    url: '{{ route('audit.store') }}',
                    type: 'POST',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    success: function(data)
                    {
                        $("#addSubmit").prop('disabled', false);
                        if (!data.error2)
                            swal("Successful!", data.success, "success")
                                .then((action) => {
                                    window.location.href = '{{ route('audit.index') }}';
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
                var model_id = $(this).attr("data-id");
                var url = "{{ route('audit.edit', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        editFormBehaviour();
                        if (!data.error)
                        {
                            $("#editForm input[name='edit_model_id']").val(data.audit.id);
                            $("#editForm select[name='department_id']").html(data.departmentHtml);
                            $("#editForm input[name='date']").val(data.audit.date);
                            $("#editForm .edit_file").html(data.fileHtml);
                            $("#editForm textarea[name='description']").val(data.audit.description);
                            $("#editForm textarea[name='remark']").val(data.audit.remark);
                        }
                        else
                        {
                            alert(data.error);
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        alert("Some thing went wrong");
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
                    var url = "{{ route('audit.update', ":model_id") }}";
                    //
                    $.ajax({
                        url: url.replace(':model_id', model_id),
                        type: 'POST',
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function(data)
                        {
                            $("#editSubmit").prop('disabled', false);
                            if (!data.error2)
                                swal("Successful!", data.success, "success")
                                    .then((action) => {
                                        window.location.href = '{{ route('audit.index') }}';
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
                    title: "Are you sure to delete this audit file?",
                    // text: "Make sure if you have filled Vendor details before proceeding further",
                    icon: "info",
                    buttons: ["Cancel", "Confirm"]
                })
                .then((justTransfer) =>
                {
                    if (justTransfer)
                    {
                        var model_id = $(this).attr("data-id");
                        var url = "{{ route('audit.destroy', ":model_id") }}";

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
    @endpush

</x-admin.layout>
