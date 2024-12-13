<x-admin.layout>
    <x-slot name="title">Signature</x-slot>
    <x-slot name="heading">Signature</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


        <!-- Add Form -->
        <div class="row" id="addContainer" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <h4 class="card-title">Add Signature</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="selectType">Select Signature Type <span class="text-danger">*</span></label>
                                    <select required id="selectType" class="form-select selectType">
                                        <option value="MCA">MCA</option>
                                        <option value="Department HOD">Department HOD</option>
                                    </select>
                                    <span class="text-danger is-invalid signatureMca department_id_err"></span>
                                </div>
                                <div class="col-md-4 d-none departmentDiv">
                                    <label class="col-form-label" for="department_id">Select Department<span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid department_id_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="images">Select signature <span class="text-danger">*</span></label>
                                    <input class="form-control" id="images" name="images" accept="image/*" type="file" required placeholder="Enter Department image">
                                    <div class="signature-message text-danger">
                                        Note:- Signature size should be 120px X 60px.
                                    </div>
                                    <span class="text-danger is-invalid images_err"></span>
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
                            <h4 class="card-title">Edit Signature</h4>
                        </div>
                        <div class="card-body py-2">
                            <input type="hidden" id="edit_model_id" name="edit_model_id" value="">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="selectType">Select Signature Type <span class="text-danger">*</span></label>
                                    <select id="selectType" class="form-select selectType">
                                        <option value="MCA">MCA</option>
                                        <option value="Department HOD">Department HOD</option>
                                    </select>
                                    <span class="text-danger is-invalid signatureMca department_id_err"></span>
                                </div>
                                <div class="col-md-4 d-none departmentDiv">
                                    <label class="col-form-label" for="department_id">Select Department<span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid department_id_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="images">Select signature <span class="text-danger">*</span></label>
                                    
                                    <input class="form-control" id="images" name="images" accept="image/*" type="file" required placeholder="Enter Department image">
                                    <div class="signature-message text-danger">
                                        Note:- Signature size should be 120px X 60px.
                                    </div>
                                    <span class="text-danger is-invalid images_err"></span>
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
                                        <th>image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($signatures as $signature)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $signature->department?->name ?? "MCA" }}</td>
                                            <td><img src="{{ asset('storage/'. $signature->image) }}" width="150px" alt=""></td>
                                            <td>
                                                <button class="edit-element btn text-secondary px-2 py-1" title="Edit signature" data-id="{{ $signature->id }}"><i data-feather="edit"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




</x-admin.layout>


{{-- Add --}}
<script>
    $('#selectType').change(function(){
        let type = $(this).val();
        if(type == "MCA"){
            $('.departmentDiv').addClass('d-none');
            $('.signatureMca').removeClass('d-none');
        }else{
            $('.departmentDiv').removeClass('d-none');
            $('.signatureMca').addClass('d-none');
        }
    });

    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('signature.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            beforeSend: function()
            {
                $('#preloader').css('opacity', '0.5');
                $('#preloader').css('visibility', 'visible');
            },
            success: function(data)
            {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                        .then((action) => {
                            window.location.href = '{{ route('signature.index') }}';
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
            },
            complete: function() {
                $('#preloader').css('opacity', '0');
                $('#preloader').css('visibility', 'hidden');
            },
        });

    });
</script>


<!-- Edit -->
<script>
    $("#buttons-datatables").on("click", ".edit-element", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('signature.edit', ":model_id") }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            beforeSend: function()
            {
                $('#preloader').css('opacity', '0.5');
                $('#preloader').css('visibility', 'visible');
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();
                if (!data.error)
                {
                    $("#editForm input[name='edit_model_id']").val(data.signature.id);
                    if(data.signature.department_id){
                        $('#editForm #selectType').val('Department HOD');
                        $('#editForm select[name="department_id"]').val(data.signature.department_id);
                        $('#editForm .departmentDiv').removeClass('d-none');
                    }else{
                        $('#editForm #selectType').val('MCA');
                        $('#editForm select[name="department_id"]').val("");
                        $('#editForm .departmentDiv').addClass('d-none');
                    }
                    if(data.signature.image != ""){
                        $('#viewSignature').removeClass('d-none');
                        $('#viewSignature').attr('href', "{{ asset('storage/') }}/"+data.signature.image);
                    }else{
                        $('#viewSignature').addClass('d-none')
                    }

                    // $("#editForm input[name='initial']").val(data.signature.initial);
                    $("#editForm select[name='status']").val(data.signature.status);
                }
                else
                {
                    alert(data.error);
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                alert("Some thing went wrong");
            },
            complete: function() {
                $('#preloader').css('opacity', '0');
                $('#preloader').css('visibility', 'hidden');
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
            var url = "{{ route('signature.update', ":model_id") }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                beforeSend: function()
                {
                    $('#preloader').css('opacity', '0.5');
                    $('#preloader').css('visibility', 'visible');
                },
                success: function(data)
                {
                    $("#editSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                            .then((action) => {
                                window.location.href = '{{ route('signature.index') }}';
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
                },
                complete: function() {
                    $('#preloader').css('opacity', '0');
                    $('#preloader').css('visibility', 'hidden');
                },
            });

        });
    });
</script>
