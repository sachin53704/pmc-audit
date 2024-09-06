<x-admin.layout>
    <x-slot name="title">{{ ucfirst($status) }} Programme Audit List</x-slot>
    <x-slot name="heading">{{ ucfirst($status) }} Programme Audit List</x-slot>
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
                                    <th>Assign Auditor</th>
                                    @if(Request()->status == "pending" || Request()->status == "rejected")
                                    <th>Status</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $audit->department?->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->date)->format('d-m-Y') }}</td>
                                        <td>{{ Str::limit($audit->description, '85') }}</td>
                                        <td>{{ Str::limit($audit->remark, '85') }}</td>
                                        <td>
                                            <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                        </td>
                                        <td>
                                            @foreach($audit->assignedAuditors as $auditor)
                                            {{ $loop->iteration.'. '.$auditor?->user?->first_name.' '.$auditor?->user?->middle_name.' '.$auditor?->user?->last_name }}<br>
                                            @endforeach
                                        </td>
                                        @if(Request()->status == "pending" || Request()->status == "rejected")
                                        <td>
                                            @if($audit->dymca_status == 3)
                                            {{ $audit->dymca_remark }}
                                            @elseif($audit->mca_status == 3)
                                            {{ $audit->mca_remark }}
                                            @else
                                            <span class="badge bg-secondary">{{ $audit->status_name }}</span>
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            @if(Auth::user()->hasRole('MCA') && $audit->mca_status == "1" || Auth::user()->hasRole('DY MCA') && $audit->dymca_status == "1")
                                                <button class="btn btn-success approve-audit px-2 py-1" data-action="approve" title="Approve" data-id="{{ $audit->id }}"><i data-feather="check-circle"></i> Approve</button>
                                                <button class="btn btn-danger reject-audit px-2 py-1" data-action="reject" title="Reject" data-id="{{ $audit->id }}"><i data-feather="x-circle"></i> Reject</button>
                                            @endif
                                            {{-- @if($status == 'pending' && isset($page_type) && $page_type != 'assign_auditor') --}}
                                            @if (isset($page_type) && $page_type == 'assign_auditor')
                                                @if(count($audit->assignedAuditors) > 0)
                                                    <button class="btn btn-secondary px-2 py-1" title="Auditor Assigned" disabled><i data-feather="user-check"></i> Auditor Assigned</button>
                                                @else
                                                    @can('audit_list.assign')
                                                        <button class="btn btn-secondary assign-auditor px-2 py-1" title="Assign Auditor" data-id="{{ $audit->id }}"><i data-feather="user-check"></i> Assign Auditor</button>
                                                    @endcan
                                                @endif
                                            @else
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


    {{-- Assign Role Modal --}}
    <div class="modal fade" id="assignAuditorModal" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" id="assignAuditorForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Auditor</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="audit_id" name="audit_id" value="">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="name">Audit No : </label>
                            <div class="col-sm-9">
                                <h6 id="audit_no" class="pt-2"></h6>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="auditor_id">Auditor : </label>
                            <div class="col-sm-9" style="max-height: 60px">
                                <select class="js-example-basic-single form-select" multiple name="auditor_id[]" id="auditor_id">
                                    <option value="">--Select Auditor--</option>
                                </select>
                                <span class="text-danger is-invalid auditor_id_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="assignAuditorSubmit" type="submit">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Reject Programme Audit Modal --}}
    <div class="modal fade" id="rejectAuditModal" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" id="rejectAuditForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Audit</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="audit_id" value="">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="reject_reason">Reject Reason </label>
                            <div class="col-sm-9">
                                <textarea name="reject_reason" cols="10" rows="5" class="form-control"></textarea>
                                <span class="text-danger is-invalid reject_reason_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="rejectAuditSubmit" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <!-- Open Assign Auditor Modal -->
        <script>
            $("#buttons-datatables").on("click", ".assign-auditor", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                var url = "{{ route('audit.get-auditors', ':model_id') }}";
                $('#audit_id').val(model_id);

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
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error)
                        {
                            $("#assignAuditorModal #audit_id").val(data.audit.id);
                            $("#assignAuditorModal #audit_no").text(data.audit.audit_no);
                            $("#assignAuditorModal #auditor_id").html(data.auditorsHtml);
                            $("#assignAuditorModal").modal("show");
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
            $("#assignAuditorForm").submit(function(e) {
                e.preventDefault();
                $("#assignAuditorSubmit").prop('disabled', true);

                var formdata = new FormData(this);
                formdata.append('_method', 'PUT');
                var model_id = $('#audit_id').val();
                var url = "{{ route('audit.assign-auditor', ':model_id') }}";

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
                    success: function(data) {
                        $("#assignAuditorSubmit").prop('disabled', false);
                        if (!data.error2)
                            swal("Successful!", data.success, "success")
                            .then((action) => {
                                $("#assignAuditorModal").modal('hide');
                                window.location.reload();
                            });
                        else
                            swal("Error!", data.error2, "error");
                    },
                    statusCode: {
                        422: function(responseObject, textStatus, jqXHR) {
                            $("#assignAuditorSubmit").prop('disabled', false);
                            resetErrors();
                            printErrMsg(responseObject.responseJSON.errors);
                        },
                        500: function(responseObject, textStatus, errorThrown) {
                            $("#assignAuditorSubmit").prop('disabled', false);
                            swal("Error occured!", "Something went wrong please try again", "error");
                        }
                    },
                    complete: function() {
                        $('#preloader').css('opacity', '0');
                        $('#preloader').css('visibility', 'hidden');
                    },
                });

                function resetErrors() {
                    var form = document.getElementById('assignAuditorForm');
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


        <!-- Open reject modal for programme audit -->
        <script>
            $("#buttons-datatables").on("click", ".reject-audit", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                $("#rejectAuditForm [name='audit_id']").val(model_id);
                $("#rejectAuditModal").modal("show");
            });

            $("#rejectAuditForm").submit(function(e) {
                e.preventDefault();
                $("#rejectAuditSubmit").prop('disabled', true);
                var model_id = $("#rejectAuditForm [name='audit_id']").val();
                var url = "{{ route('audit.status-change', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'POST',
                    data: {
                        '_method': "PUT",
                        'action': "reject",
                        'reject_reason': $("#rejectAuditForm [name='reject_reason']").val(),
                        '_token': "{{ csrf_token() }}"
                    },
                    beforeSend: function()
                    {
                        $('#preloader').css('opacity', '0.5');
                        $('#preloader').css('visibility', 'visible');
                    },
                    success: function(data)
                    {
                        $("#rejectAuditSubmit").prop('disabled', false);
                        if (!data.error2)
                            swal("Successful!", data.success, "success")
                                .then((action) => {
                                    window.location.reload();
                                });
                        else
                            swal("Error!", data.error2, "error");
                    },
                    statusCode: {
                        422: function(responseObject, textStatus, jqXHR) {
                            $("#rejectAuditSubmit").prop('disabled', false);
                            resetErrors();
                            console.log(responseObject.responseJSON);
                            printErrMsg(responseObject.responseJSON.errors);
                        },
                        500: function(responseObject, textStatus, errorThrown) {
                            $("#rejectAuditSubmit").prop('disabled', false);
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


        <!-- Approve programme audit -->
        <script>
            $("#buttons-datatables").on("click", ".approve-audit", function(e) {
                e.preventDefault();
                var action  = $(this).attr("data-action");
                swal({
                    title: "Are you sure to "+action+" this programme audit",
                    icon: "warning",
                    buttons: ["Cancel", "Confirm"]
                })
                .then((confirm) =>
                {
                    if (confirm)
                    {
                        var model_id = $(this).attr("data-id");
                        var url = "{{ route('audit.status-change', ":model_id") }}";

                        $.ajax({
                            url: url.replace(':model_id', model_id),
                            type: 'POST',
                            data: {
                                '_method': "PUT",
                                'action': "approve",
                                '_token': "{{ csrf_token() }}"
                            },
                            beforeSend: function()
                            {
                                $('#preloader').css('opacity', '0.5');
                                $('#preloader').css('visibility', 'visible');
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
                            complete: function() {
                                $('#preloader').css('opacity', '0');
                                $('#preloader').css('visibility', 'hidden');
                            },
                        });
                    }
                });
            });
        </script>
    @endpush


</x-admin.layout>



