<x-admin.layout>
    <x-slot name="title">Compliance Objections</x-slot>
    <x-slot name="heading">Compliance Objections</x-slot>
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
                                    {{-- <th>Status</th> --}}
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
                                        <td><span style="cursor: pointer" title="{{ $audit->description }}">{{ Str::limit($audit->description, '30') }}<span></td>
                                        <td><span style="cursor: pointer" title="{{ $audit->remark }}">{{ Str::limit($audit->remark, '30') }}</span></td>
                                        <td>
                                            <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                        </td>
                                        {{-- <td>
                                            <span class="badge bg-secondary">{{ $audit->status_name }}</span>
                                        </td> --}}
                                        <td>
                                            <a href="{{ asset($audit->dl_file_path) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                        </td>
                                        <td><span style="cursor: pointer" title="{{ $audit->dl_description }}">{{ Str::limit($audit->dl_description, '30') }}</span></td>
                                        <td>
                                            <button class="btn btn-secondary edit-element px-2 py-1" title="View compliance objection" data-controls-modal="addObjectionModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}"><i data-feather="file-text"></i> View Compliance</button>
                                            {{-- <button class="btn text-secondary edit-element px-2 py-1" title="Add Compliance" data-id="{{ $audit->id }}"><i data-feather="file-text"></i></button> --}}
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
                        <div id="modelObjectionId"></div>

                        <div id="viewObjectionDetails" class="d-none">
                            <hr>
                            <input type="hidden" name="audit_objection_id" value="" id="audit_objection_id">
                            <input type="hidden" name="audit_id" value="" id="audit_id">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="objection_no">HMM No. <span class="text-danger">*</span></label>
                                    <input type="text" name="objection_no" id="objection_no" class="form-control" disabled value="{{ time() }}">
                                    <span class="text-danger is-invalid objection_no_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                    <input type="date" name="entry_date" disabled id="entry_date" class="form-control">
                                    <span class="text-danger is-invalid entry_date_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="department_id">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" disabled id="department_id" class="form-select">
                                        <option value="">Select department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid department_id_err"></span>
                                </div>
                        
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="zone_id">Zone <span class="text-danger">*</span></label>
                                    <select name="zone_id" id="zone_id" disabled class="form-select">
                                        <option value="">Select zone</option>
                                        @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid zone_id_err"></span>
                                </div>
                            
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="from_year">From Year <span class="text-danger">*</span></label>
                                    <select name="from_year" id="from_year" disabled class="form-select">
                                        <option value="">Select from year</option>
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid from_year_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="to_year">To Year <span class="text-danger">*</span></label>
                                    <select name="to_year" id="to_year" disabled class="form-select">
                                        <option value="">Select to year</option>
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid to_year_err"></span>
                                </div>
                            
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="audit_type_id">Audit Type <span class="text-danger">*</span></label>
                                    <select name="audit_type_id" id="audit_type_id" disabled class="form-select">
                                        <option value="">Select audit type</option>
                                        @foreach($auditTypes as $auditType)
                                        <option value="{{ $auditType->id }}">{{ $auditType->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid audit_type_id_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="severity_id">Severity <span class="text-danger">*</span></label>
                                    <select name="severity_id" disabled id="severity_id" class="form-select">
                                        <option value="">Select severity</option>
                                        @foreach($severities as $severity)
                                        <option value="{{ $severity->id }}">{{ $severity->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid severity_id_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="audit_para_category_id">Audit Para Category <span class="text-danger">*</span></label>
                                    <input type="hidden" disabled name="audit_para_value" id="auditParaValue">
                                    <select name="audit_para_category_id" disabled id="audit_para_category_id" class="form-select">
                                        <option data-amount="" value="">Select option</option>
                                        @foreach($auditParaCategory as $auditParaCat)
                                        <option data-amount="{{ $auditParaCat->is_amount }}" value="{{ $auditParaCat->id }}">{{ $auditParaCat->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid audit_para_category_id_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3 d-none isAmountDisplayOrNot">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" disabled id="amount" class="form-control">
                                    <span class="text-danger is-invalid amount_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" disabled id="subject" class="form-control">
                                    <span class="text-danger is-invalid subject_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="work_name">Work Name</label>
                                    <input type="text" name="work_name" disabled id="work_name" class="form-control">
                                    <span class="text-danger is-invalid work_name_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="contractor_name">Contractor Name</label>
                                    <input type="text" name="contractor_name" disabled id="contractor_name" class="form-control">
                                    <span class="text-danger is-invalid contractor_name_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <a href="#" id="documentFile" target="_blank" class="btn btn-primary mt-4">View File</a>
                                </div>
                            
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <label for="sub_unit">No. of Objection <span class="text-danger">*</span></label>
                                    <input type="text" name="sub_unit" disabled id="sub_unit" class="form-control">
                                    <span class="text-danger is-invalid sub_unit_err"></span>
                                </div>
                            </div>


                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea type="text" name="description" id="description" class="form-control"></textarea>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-lg-6 col-md-6 col-12 mb-3">
                                    <label for="mca_action_status">Status <span class="text-danger">*</span></label>
                                    <select name="mca_action_status" disabled id="mca_action_status" class="form-select">
                                        <option value="0">Select value</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Forward to department</option>
                                    </select>
                                    <span class="text-danger is-invalid mca_action_status_err"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 mb-3">
                                    <label for="mca_remark">MCA Remark <span class="text-danger">*</span></label>
                                    <textarea type="text" name="mca_remark" id="mca_remark" class="form-control" disabled></textarea>
                                    <span class="text-danger is-invalid mca_remark_err"></span>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="table-responsive" id="tableDepartmentAnswer">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer d-none" id="viewFooterObjectionDetails">
                        <button class="btn btn-secondary close-modal" data-bs-dismiss="modal" type="button" >Cancel</button>
                        <button class="btn btn-primary" id="saveObjectionStatus" type="submit">Submit</button>
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

        </script>


        <!-- Edit -->
        <script>
            $("#buttons-datatables").on("click", ".edit-element", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                var url = "{{ route('objection.get-assign-objection') }}";

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
                    success: function(data, textStatus, jqXHR) {
                        editFormBehaviour();
                        if (!data.error)
                        {
                            $('#modelObjectionId').html(data.objectionHtml);

                            $("#addObjectionModal").modal("show");
                        } else {
                            swal("Error!", data.error, "error");
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


        <!-- Approve Reject Answers -->
        <script>
            $('body').on('click', '.viewObjection', function(){
                let id = $(this).attr('data-id');

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
                        $('#addForm #audit_objection_id').val(data.auditObjection.id)
                        $("#addForm input[name='audit_id']").val(data.auditObjection.audit_id);
                        $("#addForm input[name='objection_no']").val(data.auditObjection.objection_no);
                        $("#addForm input[name='entry_date']").val(data.auditObjection.entry_date);
                        $("#addForm select[name='department_id']").val(data.auditObjection.department_id);
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
                        $("#addForm input[name='work_name']").val(data.auditObjection.work_name);
                        $("#addForm input[name='contractor_name']").val(data.auditObjection.contractor_name);

                        if(data.auditObjection.document && data.auditObjection.document != ""){
                            var file = "{{ asset('storage') }}/"+data.auditObjection.document;
                        }else{
                            var file = "javascript:void(0)";
                        }
                        $("#addForm #documentFile").attr('href', file);
                        $("#addForm input[name='sub_unit']").val(data.auditObjection.sub_unit);
                        // $("#addForm textarea[name='description']").val(data.auditObjection.desc
                        editorInstance.setData(data.auditObjection.description);
                        $('#tableDepartmentAnswer').html(data.auditDepartmentAnswerHtml)

                        // $('#mca_action_status').val(data.auditObjection.mca_action_status)
                        // $('#mca_remark').val(data.auditObjection.mca_remark)


                        $('#viewObjectionDetails').removeClass('d-none');
                        $('#viewFooterObjectionDetails').removeClass('d-none');
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


        <script>
            $("#addForm").submit(function(e) {
                e.preventDefault();
                var model_id = $('#audit_objection_id').val();
                // $('#audit_id').val(model_id)
                var url = "{{ route('objection.change-objection-status') }}";
                var audit_id = $('#audit_id').val();
                // let description = editordepartmentInstance.getData();
                var formdata = new FormData(this);
                
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
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error){
                            swal("Successful!", data.success, "success")
                                .then((action) => {
                                    window.location.reload();
                                });
                        }
                        else{
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
            });
        </script>
    @endpush


</x-admin.layout>



