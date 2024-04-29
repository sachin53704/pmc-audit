<x-admin.admin-layout>
    <x-slot name="title">Change Password</x-slot>

    <div class="page-body">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
            <div class="col-sm-6">
                <h3>Change Password</h3>
            </div>
            <div class="col-sm-6">

            </div>
            </div>
        </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
        <div class="row card">
            <div class="col-sm-12 col-xl-8 mx-auto">
            <div class="row">

                <div class="col-sm-12">
                <div class="">
                    <div class="card-header pb-0">
                    </div>
                    <form class="theme-form" id="changePasswordForm">
                        @csrf
                        <div class="card-body">


                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="old_password">Old Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="old_password" name="old_password" placeholder="Old Password">
                                        <span class="input-group-text" id="old_password_eye" onclick="showHidePassword1()"><i class="eye fa-regular fa-eye-slash"></i></span>
                                    </div>
                                    <span class="text-danger is-invalid old_password_err"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="password">New Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="password" name="password" placeholder="New Password">
                                        <span class="input-group-text" id="password_eye" onclick="showHidePassword2()"><i class="eye fa-regular fa-eye-slash" ></i></span>
                                    </div>
                                    <span class="text-danger is-invalid password_err"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="password">Confirm Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                        <span class="input-group-text" id="confirm_password_eye" onclick="showHidePassword3()"><i class="eye fa-regular fa-eye-slash" ></i></span>
                                    </div>
                                    <span class="text-danger is-invalid confirm_password_err"></span>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" id="changePasswordSubmit">Submit</button>
                            <button class="btn btn-secondary" type="reset">Reset</button>
                        </div>
                    </form>
                </div>
                </div>

            </div>
            </div>

        </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

</x-admin.admin-layout>


<script>
    $("#changePasswordForm").submit(function(e) {
        e.preventDefault();
        $("#changePasswordSubmit").prop('disabled', true);
        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('change-password') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                if (!data.error && !data.error2) {
                    swal("Successful!", data.success, "success")
                        .then((action) => {
                            window.location.href = '{{ route('dashboard') }}';
                        });
                } else {
                    if (data.error2) {
                        swal("Error!", data.error2, "error");
                        $("#changePasswordSubmit").prop('disabled', false);
                    } else {
                        $("#changePasswordSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(data.error);
                    }
                }
            },
            error: function(error) {
                $("#changePasswordSubmit").prop('disabled', false);
                swal("Error occured!", "Something went wrong please try again", "error");
            },
        });

        function resetErrors() {
            var form = document.getElementById('changePasswordForm');
            var data = new FormData(form);
            for (var [key, value] of data)
            {
                $('.' + key + '_err').text('');
                $('#' + key).removeClass('is-invalid');
                $('#' + key).addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value)
            {
                $('.' + key + '_err').text(value);
                $('#' + key).addClass('is-invalid');
            });
        }

    });
</script>

<script>

    showHidePassword1 = () => {
        var password = document.getElementById('old_password');
        var toggler = document.getElementById('old_password_eye');

        if (password.type == 'password') {
            password.setAttribute('type', 'text');

            toggler.querySelector('i').classList.remove('fa-eye-slash');
            toggler.querySelector('i').classList.add('fa-eye');
        }
        else
        {
            password.setAttribute('type', 'password');
            toggler.querySelector('i').classList.remove('fa-eye');
            toggler.querySelector('i').classList.add('fa-eye-slash');
        }
    };

    showHidePassword2 = () => {
        var password = document.getElementById('password');
        var toggler = document.getElementById('password_eye');

        if (password.type == 'password') {
            password.setAttribute('type', 'text');

            toggler.querySelector('i').classList.remove('fa-eye-slash');
            toggler.querySelector('i').classList.add('fa-eye');
        }
        else
        {
            password.setAttribute('type', 'password');
            toggler.querySelector('i').classList.remove('fa-eye');
            toggler.querySelector('i').classList.add('fa-eye-slash');
        }
    };

    showHidePassword3 = () => {
        var password = document.getElementById('confirm_password');
        var toggler = document.getElementById('confirm_password_eye');

        if (password.type == 'password') {
            password.setAttribute('type', 'text');

            toggler.querySelector('i').classList.remove('fa-eye-slash');
            toggler.querySelector('i').classList.add('fa-eye');
        }
        else
        {
            password.setAttribute('type', 'password');
            toggler.querySelector('i').classList.remove('fa-eye');
            toggler.querySelector('i').classList.add('fa-eye-slash');
        }
    };
</script>
