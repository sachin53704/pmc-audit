<x-admin.layout>
    <x-slot name="title">Assigned Audit List</x-slot>
    <x-slot name="heading">Assigned Audit List</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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
                                    <th>View Letter</th>
                                    <th>Letter Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $audit->department?->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->date)->format('d-m-Y') }}</td>
                                        <td><span style="cursor: pointer" title="{{ $audit->description }}">{{ Str::limit($audit->description, '30') }}</span></td>
                                        <td><span style="cursor: pointer" title="{{ $audit->remark }}">{{ Str::limit($audit->remark, '85') }}</span></td>
                                        <td>
                                            @if($audit->file_path)
                                                <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($audit->dl_file_path)
                                                <a href="{{ asset($audit->dl_file_path) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($audit->dl_description, '85') }}</td>
                                        <td>
                                            @if($audit->dl_file_path)
                                                <button class="btn btn-secondary px-2 py-1" title="letter is sent to department" disabled><i data-feather="file-text"></i> Letter Sent</button>
                                            @else
                                                @can('send_letter.department')
                                                    <button class="btn btn-secondary send-dept-letter px-2 py-1" title="Send letter to department" data-id="{{ $audit->id }}"><i data-feather="file-text"></i> Send Letter</button>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Send Department Letter Modal --}}
    <div class="modal fade" id="sendLetterModal" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" id="sendLetterForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Send Letter to the Department </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="audit_id" name="audit_id" value="">
                        <input type="hidden" id="department_id" name="department_id" value="">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="audit_id">Audit No : </label>
                            <div class="col-sm-9">
                                <h6 id="audit_no" class="pt-2"></h6>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="department_name">Department Name : </label>
                            <div class="col-sm-9">
                                <h6 id="department_name" class="pt-2"></h6>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="description">Description <span class="text-danger">*</span></label>
                            <div class="col-sm-9" >
                                <textarea name="description" id="description" cols="10" rows="5" class="form-control" required></textarea>
                                <span class="text-danger is-invalid description_err"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="letter_file">Upload Letter <span class="text-danger">*</span></label>
                            <div class="col-sm-9" >
                                <input type="file" name="letter_file" id="letter_file" class="form-control" required accept=".pdf, .jpg, .jpeg, .png">
                                <span class="text-danger is-invalid letter_file_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="sendLetterSubmit" type="submit">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <!-- Open Send Department Letter Modal -->
        <script>
            $("#buttons-datatables").on("click", ".send-dept-letter", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                var url = "{{ route('get-audit-info') }}";

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'audit_id': model_id
                    },
                    beforeSend: function()
                    {
                        $('#preloader').css('opacity', '0.5');
                        $('#preloader').css('visibility', 'visible');
                    },
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error)
                        {
                            $("#sendLetterModal #audit_id").val(data.audit.id);
                            $("#sendLetterModal #department_id").text(data.audit.department_id);
                            $("#sendLetterModal #audit_no").text(data.audit.audit_no);
                            $("#sendLetterModal #department_name").text(data.audit.department?.name);

                            $("#sendLetterModal").modal("show");
                        } else {
                            swal("Error!", data.error, "error");
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal("Error!", "Some thing went wrong", "error");
                    },
                    complete: function() {
                        $('#preloader').css('opacity', '0');
                        $('#preloader').css('visibility', 'hidden');
                    },
                });

                $('#assign-role-modal').modal('show');
            });
        </script>

        <!-- Assign Auditor to the Audit file -->
        <script>
            $("#sendLetterForm").submit(function(e) {
                e.preventDefault();
                $("#sendLetterSubmit").prop('disabled', true);

                var formdata = new FormData(this);
                var model_id = $('#audit_id').val();
                var url = "{{ route('send-letter') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    beforeSend: function()
                    {
                        $('#preloader').css('opacity', '0.5');
                        $('#preloader').css('visibility', 'visible');
                    },
                    success: function(data) {
                        $("#sendLetterSubmit").prop('disabled', false);
                        if (!data.error2)
                            swal("Successful!", data.success, "success")
                            .then((action) => {
                                $("#sendLetterModal").modal('hide');
                                window.location.reload();
                            });
                        else
                            swal("Error!", data.error2, "error");
                    },
                    statusCode: {
                        422: function(responseObject, textStatus, jqXHR) {
                            $("#sendLetterSubmit").prop('disabled', false);
                            resetErrors();
                            printErrMsg(responseObject.responseJSON.errors);
                        },
                        500: function(responseObject, textStatus, errorThrown) {
                            $("#sendLetterSubmit").prop('disabled', false);
                            swal("Error occured!", "Something went wrong please try again", "error");
                        }
                    },
                    complete: function() {
                        $('#preloader').css('opacity', '0');
                        $('#preloader').css('visibility', 'hidden');
                    },
                });

                function resetErrors() {
                    var form = document.getElementById('sendLetterForm');
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
    @endpush


</x-admin.layout>



