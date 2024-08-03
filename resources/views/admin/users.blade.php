<x-admin.layout>
    <x-slot name="title">Users</x-slot>
    <x-slot name="heading">Users</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <!-- Add Form Start -->
    <div class="row" id="addContainer" style="display:none;">
        <div class="col-sm-12">
            <div class="card">
                <form class="theme-form" name="addForm" id="addForm">
                    @csrf
                    <div class="card-header pb-0">
                        <h4>Create User</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3 row">
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="first_name" type="text" placeholder="Enter First Name">
                                <span class="text-danger is-invalid first_name_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="middle_name">Middle Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="middle_name" type="text" placeholder="Enter Middle Name">
                                <span class="text-danger is-invalid middle_name_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="last_name" type="text" placeholder="Enter Last Name">
                                <span class="text-danger is-invalid last_name_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="others">Others</option>
                                </select>
                                <span class="text-danger is-invalid gender_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="mobile">User Mobile <span class="text-danger">*</span></label>
                                <input class="form-control" id="mobile" name="mobile" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                    placeholder="Enter User Mobile">
                                <span class="text-danger is-invalid mobile_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="email">User Email <span class="text-danger">*</span></label>
                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter User Email">
                                <span class="text-danger is-invalid email_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="role">Select User Type / Role <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select col-sm-12" id="role" name="role">
                                    <option value="">--Select Role--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid role_err"></span>
                            </div>

                            <div class="col-md-4 mt-3 auditor_no_field d-none">
                                <label class="col-form-label" for="auditor_no">Enter Auditor No <span class="text-danger">*</span></label>
                                <input class="form-control" name="auditor_no" type="text" maxlength="50" placeholder="Enter Auditor No">
                                <span class="text-danger is-invalid auditor_no_err"></span>
                            </div>

                            <div class="col-md-4 mt-3 department_field d-none">
                                <label class="col-form-label" for="department_id">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach ($departments->where('is_audit', 0) as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid department_id_err"></span>
                            </div>

                            <div class="col-md-4 mt-3 home_department_field d-none">
                                <label class="col-form-label" for="home_department_id">Home Department <span class="text-danger">*</span></label>
                                <select name="home_department_id" class="form-select">
                                    <option value="">Select Home Department</option>
                                    @foreach ($departments->where('is_audit', 1) as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid home_department_id_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="username">Enter Username <span class="text-danger">*</span></label>
                                <input class="form-control" name="username" type="text" placeholder="Enter Username">
                                <span class="text-danger is-invalid username_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="password">Password <span class="text-danger">*</span></label>
                                <input class="form-control" id="password" name="password" type="password" placeholder="********">
                                <span class="text-danger is-invalid password_err"></span>
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                <input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="********">
                                <span class="text-danger is-invalid confirm_password_err"></span>
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
                <section class="card">
                    <header class="card-header">
                        <h4 class="card-title">Edit User</h4>
                    </header>
                    <div class="card-body py-2">
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">
                        <div class="mb-3 row">

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="first_name" type="text" placeholder="Enter First Name">
                                <span class="text-danger is-invalid first_name_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="middle_name">Middle Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="middle_name" type="text" placeholder="Enter Middle Name">
                                <span class="text-danger is-invalid middle_name_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="last_name" type="text" placeholder="Enter Last Name">
                                <span class="text-danger is-invalid last_name_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="others">Others</option>
                                </select>
                                <span class="text-danger is-invalid gender_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="mobile">User Mobile <span class="text-danger">*</span></label>
                                <input class="form-control" name="mobile" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                    placeholder="Enter User Mobile">
                                <span class="text-danger is-invalid mobile_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="email">User Email <span class="text-danger">*</span></label>
                                <input class="form-control" name="email" type="email" placeholder="Enter User Email">
                                <span class="text-danger is-invalid email_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="role">Select User Type / Role <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select col-sm-12" name="role">
                                    <option value="">--Select Role--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid role_err"></span>
                            </div>
                            <div class="col-md-4 mt-3 auditor_no_field d-none">
                                <label class="col-form-label" for="auditor_no">Enter Auditor No <span class="text-danger">*</span></label>
                                <input class="form-control" name="auditor_no" type="text" maxlength="50" placeholder="Enter Auditor No">
                                <span class="text-danger is-invalid auditor_no_err"></span>
                            </div>
                            <div class="col-md-4 mt-3 department_field d-none">
                                <label class="col-form-label" for="department_id">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach ($departments->where('is_audit', 0) as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid department_id_err"></span>
                            </div>
                            <div class="col-md-4 mt-3 home_department_field d-none">
                                <label class="col-form-label" for="home_department_id">Home Department <span class="text-danger">*</span></label>
                                <select name="home_department_id" class="form-select">
                                    <option value="">Select Home Department</option>
                                    @foreach ($departments->where('is_audit', 1) as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid home_department_id_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="username">Enter Username <span class="text-danger">*</span></label>
                                <input class="form-control" name="username" type="text" placeholder="Enter Username">
                                <span class="text-danger is-invalid username_err"></span>
                            </div>

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
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Department</th>
                                    <th>User Type</th>
                                    <th>Registered On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->gender }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->department?->name }}</td>
                                        <td>{{ $user->roles[0]?->name }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($user->created_at)->format('d M, y h:i:s') }}
                                        </td>
                                        <td>
                                            <button class="edit-element btn btn-primary px-2 py-1" title="Edit User" data-id="{{ $user->id }}"><i data-feather="edit"></i></button>
                                            <button class="btn btn-primary change-password px-2 py-1" title="Change Password" data-id="{{ $user->id }}"><i data-feather="lock"></i></button>
                                            <button class="btn btn-warning assign-role px-2 py-1" title="Assign Role" data-id="{{ $user->id }}"><i data-feather="user-check"></i></button>
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


    {{-- Change Password Form --}}
    <div class="modal fade" id="change-password-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" id="changePasswordForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="user_id" name="user_id" value="">

                        <div class="col-8 mx-auto my-2">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group"><span class="input-group-text"><i class="fas fa-unlock-keyhole"></i></span>
                                    <input class="form-control" type="password" id="new_password" name="new_password">
                                    {{-- <div class="show-hide"><span class="show"></span></div> --}}
                                </div>
                                <span class="text-danger is-invalid password_err"></span>
                            </div>
                        </div>

                        <div class="col-8 mx-auto my-2">
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <div class="input-group"><span class="input-group-text"><i class="fas fa-unlock-keyhole"></i></span>
                                    <input class="form-control" type="password" id="confirmed_password" name="confirmed_password">
                                    {{-- <div class="show-hide"><span class="show"></span></div> --}}
                                </div>
                                <span class="text-danger is-invalid confirmed_password_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="changePasswordSubmit" type="submit">Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Assign Role Modal --}}
    <div class="modal fade" id="assign-role-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" id="assignRoleForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Role</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="role_user_id" name="role_user_id" value="">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="name">User Name : </label>
                            <div class="col-sm-9">
                                <h6 id="role_user_name" class="pt-2"></h6>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="name">Role : </label>
                            <div class="col-sm-9" style="max-height: 60px">
                                <select class="js-example-basic-single" id="edit_role" name="edit_role">
                                    <option value="">--Select Role--</option>
                                </select>
                                <span class="text-danger is-invalid edit_role_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="assignRoleSubmit" type="submit">Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-admin.layout>

<!-- Toggle Status -->
<script>
    $("#buttons-datatables").on("change", ".status", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.toggle', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                if (!data.error && !data.error2) {
                    swal("Success!", data.success, "success");
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
    });
</script>



<!-- Onchange of role dropdown -->
<script>
    $("#addForm").on("change", "[name='role']", function(e) {
        e.preventDefault();
        var roleId = $(this).val();

        $("#addForm .auditor_no_field").addClass("d-none");
        $("#addForm .home_department_field").addClass("d-none");
        $("#addForm .department_field").addClass("d-none");
        if(roleId == 4)
        {
            $("#addForm .auditor_no_field").removeClass("d-none");
            $("#addForm .home_department_field").removeClass("d-none");
        }
        else if(roleId == 7)
        {
            $("#addForm .home_department_field").removeClass("d-none");
        }
        else if(roleId == 3 || roleId == 5 || roleId == 6 || roleId == 8 || roleId == 9)
        {
            $("#addForm .department_field").removeClass("d-none");
        }
    });
    $("#editForm").on("change", "[name='role']", function(e) {
        e.preventDefault();
        var roleId = $(this).val();

        $("#editForm .auditor_no_field").addClass("d-none");
        $("#editForm .home_department_field").addClass("d-none");
        $("#editForm .department_field").addClass("d-none");
        if(roleId == 4)
        {
            $("#editForm .auditor_no_field").removeClass("d-none");
            $("#editForm .home_department_field").removeClass("d-none");
        }
        else if(roleId == 7)
        {
            $("#editForm .home_department_field").removeClass("d-none");
        }
        else if(roleId == 3 || roleId == 5 || roleId == 6 || roleId == 8 || roleId == 9)
        {
            $("#editForm .department_field").removeClass("d-none");
        }
    });
</script>


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('users.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('users.index') }}';
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


<!-- Open Change Password Modal-->
<script>
    $("#buttons-datatables").on("click", ".change-password", function(e) {
        e.preventDefault();
        var user_id = $(this).attr("data-id");
        $('#user_id').val(user_id);
        $('#change-password-modal').modal('show');
    });
</script>


<!-- Update User Password -->
<script>
    $("#changePasswordForm").submit(function(e) {
        e.preventDefault();
        $("#changePasswordSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        formdata.append('_method', 'PUT');
        var model_id = $('#user_id').val();
        var url = "{{ route('users.change-password', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#changePasswordSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        $("#change-password-modal").modal('hide');
                        $("#changePasswordSubmit").prop('disabled', false);
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#changePasswordSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#changePasswordSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('changePasswordForm');
            var data = new FormData(form);
            for (var [key, value] of data) {
                $('.' + key + '_err').text('');
                $('#' + key).removeClass('is-invalid');
                $('#' + key).addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
                $('#' + key).addClass('is-invalid');
                $('#' + key).removeClass('is-valid');
            });
        }

    });
</script>


<!-- Edit -->
<script>
    $("#buttons-datatables").on("click", ".edit-element", function(e) {
        e.preventDefault();
        // $(".edit-element").show();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();

                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.user.id);
                    $("#editForm input[name='first_name']").val(data.user.first_name);
                    $("#editForm input[name='middle_name']").val(data.user.middle_name);
                    $("#editForm input[name='last_name']").val(data.user.last_name);
                    $("#editForm select[name='gender']").val(data.user.gender);
                    $("#editForm input[name='mobile']").val(data.user.mobile);
                    $("#editForm input[name='email']").val(data.user.email);
                    $("#editForm select[name='role']").html(data.roleHtml);
                    $("#editForm input[name='username']").val(data.user.username);

                    var roleId = data.role.id;
                    $("#editForm .auditor_no_field").addClass("d-none");
                    $("#editForm .home_department_field").addClass("d-none");
                    $("#editForm .department_field").addClass("d-none");
                    if(roleId == 4)
                    {
                        $("#editForm .auditor_no_field").removeClass("d-none");
                        $("#editForm .home_department_field").removeClass("d-none");
                        $("#editForm input[name='auditor_no']").val(data.user.auditor_no);
                        $("#editForm select[name='home_department_id']").val(data.user?.department.id);
                    }
                    else if(roleId == 7)
                    {
                        $("#editForm .home_department_field").removeClass("d-none");
                        $("#editForm select[name='home_department_id']").val(data.user?.department.id);
                    }
                    else if(roleId == 3 || roleId == 5 || roleId == 6 || roleId == 8 || roleId == 9)
                    {
                        $("#editForm .department_field").removeClass("d-none");
                        $("#editForm select[name='department_id']").val(data.user?.department_id);
                    }
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
            var url = "{{ route('users.update', ':model_id') }}";
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
                            window.location.href = '{{ route('users.index') }}';
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


<!-- Open Assign Role Modal-->
<script>
    $("#buttons-datatables").on("click", ".assign-role", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.get-role', ':model_id') }}";
        $('#role_user_id').val(model_id);

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {

                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.user.id);
                    $("#edit_role").html(data.roleHtml);
                    $("#role_user_name").text(data.user.name);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });

        $('#assign-role-modal').modal('show');
    });
</script>

<!-- Update User Role -->
<script>
    $("#assignRoleForm").submit(function(e) {
        e.preventDefault();
        $("#assignRoleSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        formdata.append('_method', 'PUT');
        var model_id = $('#role_user_id').val();
        var url = "{{ route('users.assign-role', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#assignRoleSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        $("#assign-role-modal").modal('hide');
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#assignRoleSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#assignRoleSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('assignRoleForm');
            var data = new FormData(form);
            for (var [key, value] of data) {
                $('.' + key + '_err').text('');
                $('#' + key).removeClass('is-invalid');
                $('#' + key).addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
                $('#' + key).addClass('is-invalid');
                $('#' + key).removeClass('is-valid');
            });
        }

    });
</script>
