<x-admin.layout>
    <x-slot name="title">Diary</x-slot>
    <x-slot name="heading">Diary</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

        @can('diary.create')
        <!-- Add Form -->
        <div class="row" id="addContainer" style="display:none;">
            <div class="col-sm-12">
                <div class="card">
                    <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <h4 class="card-title">Add Diary</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="department_id">Select Department <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid department_id_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="working_day_id">Select Working Day <span class="text-danger">*</span></label>
                                    <select name="working_day_id" id="working_day_id" class="form-select">
                                        <option value="">Select Working Day</option>
                                        @foreach($workingDays as $workingDay)
                                        <option value="{{ $workingDay->id }}">{{ $workingDay->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid working_day_id_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input class="form-control" id="date" type="date" name="date" type="date">
                                    <span class="text-danger is-invalid date_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="work">Work <span class="text-danger">*</span></label>
                                    <textarea name="work" rows="5" class="form-control"></textarea>
                                    <span class="text-danger is-invalid work_err"></span>
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
        @endcan


        @can('diary.edit')
        {{-- Edit Form --}}
        <div class="row" id="editContainer" style="display:none;">
            <div class="col">
                <form class="form-horizontal form-bordered" method="post" id="editForm">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Diary</h4>
                        </div>
                        <div class="card-body py-2">
                            <input type="hidden" id="edit_model_id" name="edit_model_id" value="">
                            <div class="mb-3 row">

                                <div class="col-md-4">
                                    <label class="col-form-label" for="department_id">Select Department <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid department_id_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="working_day_id">Select Working Day <span class="text-danger">*</span></label>
                                    <select name="working_day_id" id="working_day_id" class="form-select">
                                        <option value="">Select Working Day</option>
                                        @foreach($workingDays as $workingDay)
                                        <option value="{{ $workingDay->id }}">{{ $workingDay->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid working_day_id_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input class="form-control" id="date" type="date" name="date" type="date">
                                    <span class="text-danger is-invalid text_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="work">Work <span class="text-danger">*</span></label>
                                    <textarea name="work" rows="5" class="form-control"></textarea>
                                    <span class="text-danger is-invalid work_err"></span>
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
        @endcan


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="">
                                    @can('diary.create')
                                    <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                    @endcan
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
                                        @if(!Auth::user()->hasRole('AG Audit') || !Auth::user()->hasRole('Auditor'))
                                        <th>User</th>
                                        @endif
                                        <th>Department</th>
                                        <th>Working Day</th>
                                        <th>Date</th>
                                        <th>Work</th>
                                        <th>Dymca Status</th>
                                        <th>MCA Status</th>
                                        @if(Auth::user()->hasRole('Auditor'))<th>Action</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($diaries as $diary)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @if(!Auth::user()->hasRole('AG Audit') || !Auth::user()->hasRole('Auditor'))
                                            <td>{{ $diary?->user?->first_name." ".$diary?->user?->middle_name." ".$diary?->user?->last_name }}</td>
                                            @endif
                                            <td>{{ $diary?->department?->name }}</td>
                                            <td>{{ $diary?->workingDay?->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($diary->date)->format('d-m-Y') }}</td>
                                            <td>{{ Str::limit($diary->work, 50) }}</td>
                                            <td>
                                                @if($diary->dymca_status == "0")
                                                <span class="badge bg-danger p-2">Rejected</span>
                                                @elseif($diary->dymca_status == "1")
                                                <span class="badge bg-success p-2">Accepted</span>
                                                @else
                                                    @if(Auth::user()->hasRole('DY MCA'))
                                                    <button class="btn btn-success btn-sm btnApprove" data-id="{{ $diary->id }}">Approve</button>
                                                    <button class="btn btn-danger btn-sm btnRejected" data-id="{{ $diary->id }}">Rejected</button>
                                                    @else
                                                    Pending
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($diary->mca_status == "0")
                                                    <span class="badge bg-danger p-2">Rejected</span>
                                                @elseif($diary->mca_status == "1")
                                                    <span class="badge bg-success p-2">Accepted</span>
                                                @else
                                                    @if(Auth::user()->hasRole('MCA'))
                                                    <button class="btn btn-success btn-sm btnApprove" data-id="{{ $diary->id }}">Approve</button>
                                                    <button class="btn btn-danger btn-sm btnRejected" data-id="{{ $diary->id }}">Rejected</button>
                                                    @else
                                                    Pending
                                                    @endif
                                                @endif
                                            </td>
                                            @if(Auth::user()->hasRole('Auditor'))
                                            <td>
                                                @if($diary->dymca_status == "1" && $diary->mca_status == "1")
                                                    -
                                                @else
                                                    @can('diary.view')
                                                    <button class="view-element btn text-primary px-1 py-1" title="View diary" data-id="{{ $diary->id }}" data-text="{{ $diary->work }}"  data-bs-toggle="modal" data-bs-target=".diaryModel"><i data-feather="eye"></i></button>
                                                    @endcan
                                                    @can('diary.edit')
                                                    <button class="edit-element btn text-secondary px-1 py-1" title="Edit diary" data-id="{{ $diary->id }}"><i data-feather="edit"></i></button>
                                                    @endcan
                                                    @can('diary.delete')
                                                    <button class="btn text-danger rem-element px-1 py-1" title="Delete diary" data-id="{{ $diary->id }}"><i data-feather="trash-2"></i> </button>
                                                    @endcan
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- view model --}}
        <div class="modal fade diaryModel" tabindex="-1" role="dialog" aria-labelledby="diaryModelLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title" id="diaryModelLabel">Diary Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body" id="diaryDetails">
                            
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        {{-- end off view model --}}




</x-admin.layout>


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('diary.store') }}',
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
                            window.location.href = '{{ route('diary.index') }}';
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
        var url = "{{ route('diary.edit', ":model_id") }}";

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
                    $("#editForm input[name='edit_model_id']").val(data.diary.id);
                    $("#editForm select[name='department_id']").val(data.diary.department_id);
                    $("#editForm select[name='working_day_id']").val(data.diary.working_day_id);
                    $("#editForm input[name='date']").val(data.diary.date);
                    $("#editForm textarea[name='work']").val(data.diary.work);
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
            var url = "{{ route('diary.update', ":model_id") }}";
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
                                window.location.href = '{{ route('diary.index') }}';
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


<!-- Delete -->
<script>
    $("#buttons-datatables").on("click", ".rem-element", function(e) {
        e.preventDefault();
        swal({
            title: "Are you sure to delete this diary?",
            // text: "Make sure if you have filled Vendor details before proceeding further",
            icon: "info",
            buttons: ["Cancel", "Confirm"]
        })
        .then((justTransfer) =>
        {
            if (justTransfer)
            {
                var model_id = $(this).attr("data-id");
                var url = "{{ route('diary.destroy', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'POST',
                    data: {
                        '_method': "DELETE",
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

<script>
    $(document).ready(function(){
        $('body').on('click', '.view-element', function(){
            let title = $(this).attr('data-text');
            $('#diaryDetails').html(title);
        });
    });
</script>




<!-- Delete -->
<script>
    $("#buttons-datatables").on("click", ".btnApprove", function(e) {
        e.preventDefault();
        swal({
            title: "Are you sure you want to approve this?",
            // text: "Make sure if you have filled Vendor details before proceeding further",
            icon: "info",
            buttons: ["Cancel", "Confirm"]
        })
        .then((justTransfer) =>
        {
            if (justTransfer)
            {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('diary-status') }}",
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id,
                        'status': 1
                        
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


    $("#buttons-datatables").on("click", ".btnRejected", function(e) {
        e.preventDefault();
        swal({
            title: "Are you sure you want to reject this?",
            // text: "Make sure if you have filled Vendor details before proceeding further",
            icon: "info",
            buttons: ["Cancel", "Confirm"]
        })
        .then((justTransfer) =>
        {
            if (justTransfer)
            {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('diary-status') }}",
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id,
                        'status': 0
                        
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
