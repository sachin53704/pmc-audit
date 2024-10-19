<x-admin.layout>
    <x-slot name="title">HMM</x-slot>
    <x-slot name="heading">HMM</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="">
                                    <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                    <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
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
                                            <td><span style="cursor: pointer" title="{{ $audit->remark }}">{{ Str::limit($audit->remark, '30') }}</span></td>
                                            <td>
                                                <button class="btn btn-info add-objection px-2 py-1" title="Add Objection" data-controls-modal="addObjectionModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}"> View Objection</button>
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    {{-- Add Objection Modal --}}
    <div class="modal fade" id="addObjectionModal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form action="" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Objection </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Department</th>
                                            <th>HMM No.</th>
                                            <th>Subject</th>
                                            <th>DYMCA Status</th>
                                            <th>DYMCA Remark</th>
                                            <th>MCA Status</th>
                                            <th>MCA Remark</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modelObjectionId">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="viewObjectionDetails d-none">
                            <hr>
                            <input type="hidden" name="audit_id" value="" id="audit_id">
                            <input type="hidden" name="audit_objection_id" value="" id="audit_objection_id">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="objection_no">HMM No. <span class="text-danger">*</span></label>
                                    <input type="text" name="objection_no" id="objection_no" class="form-control" value="" readonly>
                                </div>

                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                    <input type="date" name="entry_date" id="entry_date" class="form-control" readonly>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="department_id">Department <span class="text-danger">*</span></label>
                                    <input type="hidden" name="department_id" id="department_hidden_id">
                                    <input type="text" name="department_name_id" readonly id="department_name_id" class="form-control" />
                                </div>
                        
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="zone_id">Zone <span class="text-danger">*</span></label>
                                    <select name="zone_id" disabled id="zone_id" class="form-select">
                                        <option value="">Select zone</option>
                                        @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="from_year">From Year <span class="text-danger">*</span></label>
                                    <select name="from_year" id="from_year" disabled class="form-select">
                                        <option value="">Select from year</option>
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="to_year">To Year <span class="text-danger">*</span></label>
                                    <select name="to_year" disabled id="to_year" class="form-select">
                                        <option value="">Select to year</option>
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="audit_type_id">Audit Type <span class="text-danger">*</span></label>
                                    <select name="audit_type_id" disabled id="audit_type_id" class="form-select">
                                        <option value="">Select audit type</option>
                                        @foreach($auditTypes as $auditType)
                                        <option value="{{ $auditType->id }}">{{ $auditType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="severity_id">Severity <span class="text-danger">*</span></label>
                                    <select name="severity_id" disabled id="severity_id" class="form-select">
                                        <option value="">Select severity</option>
                                        @foreach($severities as $severity)
                                        <option value="{{ $severity->id }}">{{ $severity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="audit_para_category_id">Audit Para Category <span class="text-danger">*</span></label>
                                    <input type="hidden" name="audit_para_value" id="auditParaValue">
                                    <select name="audit_para_category_id" disabled id="audit_para_category_id" class="form-select">
                                        <option data-amount="" value="">Select option</option>
                                        @foreach($auditParaCategory as $auditParaCat)
                                        <option data-amount="{{ $auditParaCat->is_amount }}" value="{{ $auditParaCat->id }}">{{ $auditParaCat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12 mb-3 d-none isAmountDisplayOrNot">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control" readonly>
                                </div>
                                
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" id="subject" class="form-control" readonly>
                                </div>
                                
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="documents">File</label>
                                    <div>
                                        <a href="" class="btn btn-primary btn-sm" id="documentFile">View File</a>
                                    </div>
                                </div>
                            
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="sub_unit">No of Objection <span class="text-danger">*</span></label>
                                    <input readonly type="text" name="sub_unit" id="sub_unit" class="form-control">
                                </div>
                            
                                <div class="col-12 mb-3">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea type="text" name="description" id="description" class="form-control"></textarea>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="dymca_status">Select Status <span class="text-danger">*</span></label>
                                    <select name="{{ (Auth::user()->hasRole('MCA')) ? 'mca_status' : 'dymca_status' }}" class="form-select" id="dymca_status" required>
                                        <option value="">Select</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Forward To Auditor</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="dymca_remark">Remark</label>
                                    <textarea name="{{ (Auth::user()->hasRole('MCA')) ? 'mca_remark' : 'dymca_remark' }}" id="dymca_remark" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="viewObjectionDetails d-none">
                            <button class="btn btn-secondary close-modal" data-bs-dismiss="modal" type="button" >Close</button>
                            <button class="btn btn-primary" id="addObjectionSubmit" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    

@push('scripts')

    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

    <script>
        // Initialize CKEditor
        let editorInstance;
        ClassicEditor
            .create(document.querySelector('#description'),{
                toolbar: {
                    shouldNotGroupWhenFull: true
                }
            })
            .then(editor => {
                editorInstance = editor;
                editorInstance.enableReadOnlyMode('reason');
                editor.ui.view.editable.element.style.height = '200px';  // Fixed height

                // Make the editor scrollable
                editor.ui.view.editable.element.style.overflowY = 'auto';
            })
            .catch(error => {
                console.error('Error during initialization of the editor', error);
            });

        $('body').on('change', '#audit_para_category_id', function(){
            let isAmount = $(this).find(':selected').attr('data-amount')
            if(isAmount == 1){
                $('.isAmountDisplayOrNot').removeClass('d-none');
                $('#amount').prop('required', true);
                $('#auditParaValue').val(1)
            }else{
                $('.isAmountDisplayOrNot').addClass('d-none');
                $('#amount').prop('required', false);
                $('#auditParaValue').val(0)
            }
        })
    </script>


    <script>
        $('body').on('click', '.viewObjection', function(){
            let id = $(this).attr('data-id');
            let departmentName = $(this).attr('data-department-name');
            let departmentId = $(this).attr('data-department-id');

            $("#addForm #department_name_id").val(departmentName);
            $("#addForm #department_hidden_id").val(departmentId);

            $.ajax({
                url: "{{ route('view-objection') }}",
                type: 'GET',
                data: {
                    'id': id,
                },
                beforeSend: function()
                {
                    $('#preloader').css('opacity', '0.5');
                    $('#preloader').css('visibility', 'visible');
                },
                success: function(data, textStatus, jqXHR)
                {
                    $("#addForm input[name='audit_objection_id']").val(data.auditObjection.id);
                    $("#addForm input[name='audit_id']").val(data.auditObjection.audit_id);
                    $("#addForm input[name='objection_no']").val(data.auditObjection.objection_no);
                    $("#addForm input[name='entry_date']").val(data.auditObjection.entry_date);
                    // $("#addForm select[name='department_id']").val(data.auditObjection.department_id);
                    $("#addForm select[name='zone_id']").val(data.auditObjection.zone_id);
                    $("#addForm select[name='from_year']").val(data.auditObjection.from_year);
                    $("#addForm select[name='to_year']").val(data.auditObjection.to_year);
                    $("#addForm select[name='audit_type_id']").val(data.auditObjection.audit_type_id);
                    $("#addForm select[name='severity_id']").val(data.auditObjection.severity_id);
                    $("#addForm select[name='audit_para_category_id']").val(data.auditObjection.audit_para_category_id);


                    if(data.auditObjection.amount > 0){
                        $('.isAmountDisplayOrNot').removeClass('d-none');
                    }else{
                        $('.isAmountDisplayOrNot').addClass('d-none');
                    }


                    $("#addForm input[name='amount']").val(data.auditObjection.amount);
                    $("#addForm input[name='subject']").val(data.auditObjection.subject);

                    if(data.auditObjection.document && data.auditObjection.document != ""){
                        var file = "{{ asset('storage') }}/"+data.auditObjection.document;
                    }else{
                        var file = "javascript:void(0)";
                    }
                    $("#addForm #documentFile").attr('href', file);
                    $("#addForm input[name='sub_unit']").val(data.auditObjection.sub_unit);
                    // $("#addForm textarea[name='description']").val(data.auditObjection.desc
                    editorInstance.setData(data.auditObjection.description);

                    @if(Auth::user()->hasRole('MCA'))
                        $('#addForm #dymca_status').val(data.auditObjection.mca_status)
                        $('#addForm #dymca_remark').val(data.auditObjection.mca_remark)
                    @elseif(Auth::user()->hasRole('DY MCA'))
                        $('#addForm #dymca_status').val(data.auditObjection.dymca_status)
                        $('#addForm #dymca_remark').val(data.auditObjection.dymca_remark)
                    @else
                        $('#addForm #is_department_hod_forward').val(data.auditObjection.is_department_hod_forward).change()
                        $('#addForm #department_hod_remark').val(data.auditObjection.department_hod_remark)
                    @endif

                    $('.viewObjectionDetails').removeClass('d-none')
                },
                error: function(error, jqXHR, textStatus, errorThrown) {
                    swal("Error!", "Some thing went wrong", "error");
                },
                complete: function() {
                    $('#preloader').css('opacity', '0');
                    $('#preloader').css('visibility', 'hidden');
                },
            });
        });
    </script>


    {{-- Open modal and Add more --}}
    <script>
        var questionCounter = 1;

        $("#buttons-datatables").on("click", ".add-objection", function(e) {
            e.preventDefault();
            var model_id = $(this).attr("data-id");
            $('#audit_id').val(model_id)
            var url = "{{ route('ajax.viewAuditorObjection') }}";
            let status = @if(Auth::user()->hasRole('DY MCA'))1 @elseif(Auth::user()->hasRole('MCA'))2 @endif

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    'audit_id': model_id,
                    'status': status
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
                        var html = ``;
                        var count = 1;
                        $.each(data.auditObjections, function(index, value){
                            html += `<tr>
                                <td>${count++}</td>
                                <td>${value?.department?.name}</td>
                                <td>${value.objection_no}</td>
                                <td>${value.subject}</td>
                                <td>${ (value.dymca_status == "1") ? '<span class="badge bg-success">Approve</span>' : ((value.dymca_status == "2") ? '<span class="badge bg-warning">Forward To Auditor</span>' : '-') }</td>
                                <td>${ (value.dymca_remark) ? value.dymca_remark : '-' }</td>
                                <td>${ (value.mca_status == "1") ? '<span class="badge bg-success">Approve</span>' : ((value.mca_status == "2") ? '<span class="badge bg-warning">Forward To Auditor</span>' : '-') }</td>
                                <td>${ (value.mca_remark) ? value.mca_remark : '-' }</td>
                                <td><button type="button" class="btn btn-sm btn-primary viewObjection" data-id="${value.id}" data-department-name="${data.departmentName}" data-department-id="${data.department}">View Objection</button></td>
                            </tr>`;
                        });
                        $('#modelObjectionId').html(html);

                        $('.viewObjectionDetails').addClass('d-none')

                        $("#addObjectionModal").modal("show");
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


        // Submit Objection Form
        $("#addForm").submit(function(e) {
            e.preventDefault();
            $("#addObjectionSubmit").prop('disabled', true);

            var formdata = new FormData(this);

            $.ajax({
                url: '{{ route('storeHmmMcaStatus') }}',
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
                    $("#addObjectionSubmit").prop('disabled', false);
                    if (!data.error)
                        swal("Successful!", data.success, "success")
                            .then((action) => {
                                window.location.reload();
                            });
                    else
                        swal("Error!", data.error, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        $("#addObjectionSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#addObjectionSubmit").prop('disabled', false);
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

@endpush


</x-admin.layout>
