<x-admin.layout>
    <x-slot name="title">@if(Auth::user()->hasRole(['Department', 'Department HOD']))Pending Compliance @else Pending Objection @endif</x-slot>
    <x-slot name="heading">@if(Auth::user()->hasRole(['Department', 'Department HOD']))Pending Compliance @else Pending Objection @endif</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row" id="editContainer" style="display:none;">
        <div class="col">
            <form class="form-horizontal form-bordered" method="post" id="editForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">@if(Auth::user()->hasRole(['Department', 'Department HOD']))Pending Compliance @else Pending Objection @endif</h4>
                    </div>
                    <div class="card-body py-2">
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                        <div class="mb-3 row">
                            <div class="col-12" id="objectionList">
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Department</th>
                                    <th>HMM No.</th>
                                    <th>Pending objection</th>
                                    <th>View Letter</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingAuditObjections as $pendingAuditObjection)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pendingAuditObjection->auditObjection?->department?->name }}</td>
                                        <td>{{ $pendingAuditObjection->auditObjection->objection_no }}</td>                                        
                                        <td>{{ $pendingAuditObjection->sub_unit }}</td>
                                        <td>
                                            <a href="{{ asset('storage/'.$pendingAuditObjection->hmm_draft_letter) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary viewObjection px-2 py-1" title="View compliance objection" data-id="{{ $pendingAuditObjection->id }}">
                                                @if(Auth::user()->hasRole(['Department', 'Department HOD']))
                                                <i data-feather="file-text"></i> View Compliance
                                                @else
                                                <i data-feather="file-text"></i> View Objection
                                                @endif
                                            </button>
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
                       
                        <div>
                            <hr>
                            <input type="hidden" name="pending_audit_objection_id" value="" id="pending_audit_objection_id">
                            <input type="hidden" name="is_draft_save" value="" id="is_draft_save">
                            <input type="hidden" name="audit_objection_id" value="" id="audit_objection_id">
                            <input type="hidden" name="audit_id" value="" id="audit_id">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="objection_no">HMM No. <span class="text-danger">*</span></label>
                                    <input type="text" name="objection_no" id="objection_no" class="form-control" disabled value="{{ time() }}">
                                    <span class="text-danger is-invalid objection_no_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                    <input type="date" name="entry_date" disabled id="entry_date" class="form-control">
                                    <span class="text-danger is-invalid entry_date_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="department_id">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" disabled id="department_id" class="form-select">
                                        <option value="">Select department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid department_id_err"></span>
                                </div>


                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="from_year">From Year <span class="text-danger">*</span></label>
                                    <select name="from_year" id="from_year" disabled class="form-select">
                                        <option value="">Select from year</option>
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid from_year_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="to_year">To Year <span class="text-danger">*</span></label>
                                    <select name="to_year" id="to_year" disabled class="form-select">
                                        <option value="">Select to year</option>
                                        @foreach($fiscalYears as $fiscalYear)
                                        <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid to_year_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-3 col-12 mb-3">
                                    <label for="audit_type_id">Audit Type <span class="text-danger">*</span></label>
                                    <select name="audit_type_id" id="audit_type_id" disabled class="form-select">
                                        <option value="">Select audit type</option>
                                        @foreach($auditTypes as $auditType)
                                        <option value="{{ $auditType->id }}">{{ $auditType->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid audit_type_id_err"></span>
                                </div>
                                <div class="col-lg-4 col-md-3 col-12 mb-3">
                                    <label for="severity_id">Severity <span class="text-danger">*</span></label>
                                    <select name="severity_id" disabled id="severity_id" class="form-select">
                                        <option value="">Select severity</option>
                                        @foreach($severities as $severity)
                                        <option value="{{ $severity->id }}">{{ $severity->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger is-invalid severity_id_err"></span>
                                </div>
                                
                                <div class="col-lg-4 col-md-3 col-12 mb-3">
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
                                <div class="col-lg-4 col-md-3 col-12 mb-3 d-none isAmountDisplayOrNot">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" disabled id="amount" class="form-control">
                                    <span class="text-danger is-invalid amount_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-3 col-12 mb-3">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" disabled id="subject" class="form-control">
                                    <span class="text-danger is-invalid subject_err"></span>
                                </div>

                                <div class="col-lg-4 col-md-3 col-12 mb-3">
                                    <a href="#" id="documentFile" target="_blank" class="btn btn-primary mt-4">View File</a>
                                </div>

                                <div class="col-lg-4 col-md-3 col-12 mb-3">
                                    <label for="sub_unit">No of Objection <span class="text-danger">*</span></label>
                                    <input type="number" name="sub_unit" disabled id="sub_unit" class="form-control">
                                    <span class="text-danger is-invalid sub_unit_err"></span>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="description">Objection Description <span class="text-danger">*</span></label>
                                    <textarea type="text" name="description" id="description" class="form-control" disabled></textarea>
                                </div>
                            </div>

                            
                            <div class="row">
                            
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Objection Status</h4>
                                        </div><!-- end card header -->
                                        <div class="card-body">
                                            <div class="live-preview">
                                                <div class="accordion custom-accordionwithicon-plus" id="accordionWithplusicon">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample1">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button @if(!Auth::user()->hasRole('Department'))collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#departmentCompliance" aria-expanded="@if(Auth::user()->hasRole('Department'))true @endif" aria-controls="departmentCompliance">
                                                                Department Compliance 
                                                            </button>
                                                        </h2>
                                                        <div id="departmentCompliance" class="accordion-collapse collapse @if(Auth::user()->hasRole('Department'))show @endif" aria-labelledby="accordionwithplusExample1" data-bs-parent="#accordionWithplusicon">
                                                            
                                                            <div class="row px-3 py-2">
                                                                <div class="col-12 mb-3">
                                                                    <label for="department_file">Compliance File <span class="text-danger">*</span></label>
                                                                    <a href="#" class="btn btn-primary d-none complianceFile" target="_blank">View File</a>
                                                                    @if(Auth::user()->hasRole('Department'))
                                                                    <input type="file" name="department_files" id="department_file" class="form-control">
                                                                    @endif
                                                                    <span class="text-danger is-invalid department_file_err"></span>
                                                                </div>
                                                                <input type="hidden" value="0" name="departmentCompliaceFile" id="departmentCompliaceFile">

                                                                <div class="col-12 mb-3">
                                                                    <div class="d-flex justify-content-between">
                                                                        <label for="department_remark">
                                                                            Compliance Description <span class="text-danger">*</span>
                                                                        </label>
                                                                        <div id="departmentCoveringLetter">
                                                                            <a href="#" class="btn btn-primary btn-sm coveringLetter" target="_blank">Covering letter</a>
                                                                            <a href="#" class="btn btn-primary btn-sm viewFile" target="_blank">View Details</a>
                                                                        </div>
                                                                    </div>
                                                                    <textarea name="department_remark" id="department_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid department_remark_err"></span>
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <label for="submit_compliance">Submitted Compliance <span class="text-danger">*</span></label>
                                                                    <input type="number" name="submit_compliance" id="submit_compliance" class="form-control">
                                                                    <span class="text-danger is-invalid submit_compliance_err"></span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample2">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button @if(!Auth::user()->hasRole('Department HOD'))collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#departmentHodStatus" aria-expanded="@if(Auth::user()->hasRole('Department HOD'))true @endif" aria-controls="departmentHodStatus">
                                                                Department HOD Status
                                                            </button>
                                                        </h2>
                                                        <div id="departmentHodStatus" class="accordion-collapse collapse @if(Auth::user()->hasRole('Department HOD'))show @endif" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                    
                                                                <div class="col-6">
                                                                    <label for="department_hod_final_status">Department HOD Status <span class="text-danger">*</span></label>
                                                                    <select name="department_hod_final_status" class="form-select">
                                                                        <option value="">Select Status</option>
                                                                        <option value="1">Approve</option>
                                                                        <option value="0">Reject</option>
                                                                    </select>
                                                                    <span class="text-danger is-invalid department_hod_final_status_err"></span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="department_hod_final_remark">Department HOD Remark</label>
                                                                    <textarea name="department_hod_final_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid department_hod_final_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample3">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button @if(!Auth::user()->hasRole('MCA'))collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#mcaForwardToAuditor" aria-expanded="@if(Auth::user()->hasRole('MCA'))true @endif" aria-controls="mcaForwardToAuditor">
                                                                MCA Forward To Auditor
                                                            </button>
                                                        </h2>
                                                        <div id="mcaForwardToAuditor" class="accordion-collapse collapse @if(Auth::user()->hasRole('MCA'))show @endif" aria-labelledby="accordionwithplusExample3" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                    
                                                                <div class="col-6">
                                                                    <label for="department_mca_second_status">MCA Status <span class="text-danger">*</span></label>
                                                                    <select name="department_mca_second_status" class="form-select">
                                                                        <option value="">Select Status</option>
                                                                        <option value="1">Forward To Auditor</option>
                                                                    </select>
                                                                    <span class="text-danger is-invalid department_mca_second_status_err"></span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="department_mca_second_remark">MCA Remark</label>
                                                                    <textarea  name="department_mca_second_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid department_mca_second_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample2">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button @if(!Auth::user()->hasRole('Auditor'))collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#auditorStatus" aria-expanded="@if(Auth::user()->hasRole('Auditor'))true @endif" aria-controls="auditorStatus">
                                                                Auditor Status
                                                            </button>
                                                        </h2>
                                                        <div id="auditorStatus" class="accordion-collapse collapse @if(Auth::user()->hasRole('Auditor'))show @endif" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                
                                                                <div class="col-12 mb-3">
                                                                    <label for="auditor_description">Description <span class="text-danger">*</span></label>
                                                                    <textarea name="auditor_description" id="auditor_description" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid auditor_description_err"></span>
                                                                </div>

                                                                <div class="col-3 px-3 pt-2">
                                                                    <label for="auditor_status">Completed Objection <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" name="completed_sub_unit">
                                                                    <span class="text-danger is-invalid completed_sub_unit_err"></span>
                                                                </div>

                                                                <div class="col-3 px-3 pt-2">
                                                                    <label for="auditor_status">Pending Objection <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" name="pending_sub_unit">
                                                                    <span class="text-danger is-invalid pending_sub_unit_err"></span>
                                                                </div>

                                                                <div class="col-3 px-3 pt-2">
                                                                    <label for="auditor_status">Auditor Status <span class="text-danger">*</span></label>
                                                                    <select name="auditor_status" class="form-select" required>
                                                                        <option value="">Select Status</option>
                                                                        <option value="1">Proposal to Approve / Delete</option>
                                                                        <option value="0">Proposal to convert para</option>
                                                                    </select>
                                                                    <span class="text-danger is-invalid auditor_status_err"></span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <label for="auditor_remark">Auditor Remark <span class="text-danger">*</span></label>
                                                                    <textarea name="auditor_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid auditor_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample2">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button @if(!Auth::user()->hasRole('DY MCA'))collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#dymcaStatus" aria-expanded="@if(Auth::user()->hasRole('DY MCA'))true @endif" aria-controls="dymcaStatus">
                                                                DyMca Status
                                                            </button>
                                                        </h2>
                                                        <div id="dymcaStatus" class="accordion-collapse collapse @if(Auth::user()->hasRole('DY MCA'))show @endif" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                
                                                                <div class="col-6">
                                                                    <label for="dymca_final_status">Dymca Status <span class="text-danger">*</span></label>
                                                                    <select name="dymca_final_status" class="form-select">
                                                                        <option value="">Select Status</option>
                                                                        <option value="1">Approve</option>
                                                                    </select>
                                                                    <span class="text-danger is-invalid dymca_final_status_err"></span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="dymca_final_remark">Dymca Remark</label>
                                                                    <textarea name="dymca_final_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid dymca_final_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample2">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button @if(!Auth::user()->hasRole('MCA'))collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#mcaStatus" aria-expanded="@if(Auth::user()->hasRole('MCA'))true @endif" aria-controls="mcaStatus">
                                                                MCA Status
                                                            </button>
                                                        </h2>
                                                        <div id="mcaStatus" class="accordion-collapse collapse @if(Auth::user()->hasRole('MCA'))show @endif" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                
                                                                <div class="col-6">
                                                                    <label for="mca_final_status">MCA Status <span class="text-danger">*</span></label>
                                                                    <select name="mca_final_status" class="form-select">
                                                                        <option value="">Select Status</option>
                                                                        <option value="1">Approve</option>
                                                                    </select>
                                                                    <span class="text-danger is-invalid mca_final_status_err"></span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="mca_final_remark">MCA Remark</label>
                                                                    <textarea name="mca_final_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid mca_final_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div><!-- end card-body -->
                                    </div><!-- end card -->
                                </div>
                                <!--end col-->

                            </div>

                        </div>


                    </div>
                    <div class="modal-footer d-none" id="viewFooterObjectionDetails">
                        <button class="btn btn-secondary close-modal" data-bs-dismiss="modal" type="button" >Close</button>
                        @if(Auth::user()->hasRole(['Department', 'Auditor']))
                        <button class="btn btn-warning" id="saveDraftObjectionStatus" type="submit">Draft Save</button>
                        @endif
                        <button class="btn btn-primary" id="saveObjectionStatus" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

        <script>
            $(document).ready(function($q){
                $('#saveDraftObjectionStatus').click(function(){
                    $('#is_draft_save').val(1);
                });

                $('#saveObjectionStatus').click(function(){
                    $('#is_draft_save').val(0);
                });
            })

            // Initialize CKEditor
            let editorInstance;
            ClassicEditor
                .create(document.querySelector('textarea'),{
                    toolbar: {
                        shouldNotGroupWhenFull: true,
                        items: [
                            'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'code', '|',
                        'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'alignment', '|',
                        'fontSize',               // Font size options
                        'fontColor',              // Text color options
                        'fontBackgroundColor',    // Background color for text
                        '|',
                        'bulletedList', 'numberedList', 'todoList', '|', 'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                        ],
                        'format_tags': 'p;h1;h2;h3;h4;h5;h6'
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

                let deditorInstance;
                ClassicEditor
                    .create(document.querySelector('#department_remark'),{
                        toolbar: {
                            shouldNotGroupWhenFull: true,
                            items: [
                                'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'code', '|',
                            'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                            'alignment', '|',
                            'fontSize',               // Font size options
                            'fontColor',              // Text color options
                            'fontBackgroundColor',    // Background color for text
                            '|',
                            'bulletedList', 'numberedList', 'todoList', '|', 'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                            ],
                            'format_tags': 'p;h1;h2;h3;h4;h5;h6'
                        }
                    })
                    .then(editor => {
                        deditorInstance = editor;
                        let role = "{{ Auth::user()->roles[0]->name }}"
                        if(role != "Department"){
                            deditorInstance.enableReadOnlyMode('reason');
                        }
                        editor.ui.view.editable.element.style.height = '200px';  // Fixed height

                        // Make the editor scrollable
                        editor.ui.view.editable.element.style.overflowY = 'auto';
                    })
                    .catch(error => {
                        console.error('Error during initialization of the editor', error);
                    });

                let auditorDescription;
                ClassicEditor
                    .create(document.querySelector('#auditor_description'),{
                        toolbar: {
                            shouldNotGroupWhenFull: true,
                            items: [
                                'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'code', '|',
                            'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                            'alignment', '|',
                            'fontSize',               // Font size options
                            'fontColor',              // Text color options
                            'fontBackgroundColor',    // Background color for text
                            '|',
                            'bulletedList', 'numberedList', 'todoList', '|', 'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                            ],
                            'format_tags': 'p;h1;h2;h3;h4;h5;h6'
                        }
                    })
                    .then(editor => {
                        auditorDescription = editor;
                        let role = "{{ Auth::user()->roles[0]->name }}"
                        if(role != "Auditor"){
                            auditorDescription.enableReadOnlyMode('reason');
                        }
                        editor.ui.view.editable.element.style.height = '200px';  // Fixed height

                        // Make the editor scrollable
                        editor.ui.view.editable.element.style.overflowY = 'auto';
                    })
                    .catch(error => {
                        console.error('Error during initialization of the editor', error);
                    });
        </script>


        <!-- Approve Reject compliance -->
        <script>

            $("#addForm").submit(function(e) {
                e.preventDefault();
                var model_id = $('#audit_objection_id').val();
                // $('#audit_id').val(model_id)
                var url = "{{ route('pending-change-objection-status') }}";
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
                        $("#addObjectionSubmit").prop('disabled', false);
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
                    statusCode: {
                        422: function(responseObject, textStatus, jqXHR) {
                            // $("#addSubmit").prop('disabled', false);
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

                $('#assign-role-modal').modal('show');
            });


            $(document).ready(function() {
                $("#editForm").submit(function(e) {
                    e.preventDefault();
                    $("#editSubmit").prop('disabled', true);
                    var formdata = new FormData(this);
                    formdata.append('_method', 'PUT');
                    var model_id = $('#edit_model_id').val();
                    var url = "{{ route('draft-approve-answers', ":model_id") }}";
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
                                        window.location.reload();
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


            $('body').on('click', '.viewObjection', function(){
                let id = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('pending-view-objection') }}",
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
                        $('#saveObjectionStatus').removeClass('d-none');
                        $('#addForm #pending_audit_objection_id').val(data.audit.id)
                        $('#addForm #audit_objection_id').val(data.audit?.audit_objection.id)
                        $("#addForm input[name='audit_id']").val(data.audit?.audit_objection.audit_id);
                        $("#addForm input[name='objection_no']").val(data.audit?.audit_objection.objection_no);
                        $("#addForm input[name='entry_date']").val(data.audit?.auditObjection?.audit.entry_date);
                        $("#addForm select[name='department_id']").val(data.audit?.audit_objection.department_id);
                        $("#addForm select[name='from_year']").val(data.audit?.audit_objection.from_year);
                        $("#addForm select[name='to_year']").val(data.audit?.audit_objection.to_year);
                        $("#addForm select[name='audit_type_id']").val(data.audit?.audit_objection.audit_type_id);
                        $("#addForm select[name='severity_id']").val(data.audit?.audit_objection.severity_id);
                        $("#addForm select[name='audit_para_category_id']").val(data.audit?.audit_objection.audit_para_category_id);


                        if(data.audit?.audit_objection.amount > 0){
                            $('.isAmountDisplayOrNot').removeClass('d-none');
                        }else{
                            $('.isAmountDisplayOrNot').addClass('d-none');
                        }


                        $("#addForm input[name='amount']").val(data.audit?.audit_objection.amount);
                        $("#addForm input[name='subject']").val(data.audit?.audit_objection.subject);
                        if(data.audit?.audit_objection.document && data.audit?.audit_objection.document != ""){
                            var file = "{{ asset('storage') }}/"+data.audit?.audit_objection.document;
                        }else{
                            var file = "javascript:void(0)";
                        }
                        $("#addForm #documentFile").attr('href', file);
                        $("#addForm input[name='sub_unit']").val(data.audit.sub_unit);
                        // $("#addForm textarea[name='description']").val(data.audit.desc
                        editorInstance.setData(data.audit.pending_description ?? '');



                        let roleName = "{{ Auth::user()->roles[0]->name }}";
                        
                        // department status 
                        deditorInstance.setData("");                       
                        if(data.audit.department_draft_remark){
                            deditorInstance.setData(data.audit.department_draft_remark ?? '');
                        }
                        if(data.audit.department_file){
                            $('.complianceFile').removeClass('d-none');
                            $('.complianceFile').prop('href', "{{ asset('storage') }}/"+data.audit.department_file);
                            $('#departmentCompliaceFile').val(0);
                        }else{
                            $('.complianceFile').addClass('d-none'); 
                            $('#departmentCompliaceFile').val(1);
                        }
                        
                        $('#submit_compliance').val(data.audit.submit_compliance);

                        
                        if(data.audit.department_hod_final_status == "1"){
                            $('#department_file').addClass('d-none');
                            $('#submit_compliance').prop('disabled', true);
                            deditorInstance.enableReadOnlyMode('reason');
                        }

                        if(data.audit.department_remark != "" && data.audit.department_letter){
                            $('#departmentCoveringLetter').removeClass('d-none');
                            $('#departmentCoveringLetter').find('.coveringLetter').attr('href', "{{ asset('storage') }}/"+data.audit.department_letter);

                            var url = "{{ route('view-objection-pdf', [':type', ':column', ':id']) }}";
                            url = url.replace(':type', 0)
                                    .replace(':column', 'department_remark')
                                    .replace(':id', data.audit.id);

                            $('#departmentCoveringLetter').find('.viewFile').attr('href', url);
                        }else{
                            $('#departmentCoveringLetter').addClass('d-none');
                        }
                        

                        $("#addForm select[name='department_hod_final_status']").prop('disabled', false);
                        $("#addForm textarea[name='department_hod_final_remark']").prop('disabled', false);
                        $("#addForm select[name='department_hod_final_status']").val(data.audit.department_hod_final_status);
                        $("#addForm textarea[name='department_hod_final_remark']").val(data.audit.department_hod_final_remark);
                        if((data.audit.department_mca_second_status == "1")){
                            $("#addForm select[name='department_hod_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='department_hod_final_remark']").prop('disabled', true);
                        }else if(!data.audit.department_draft_remark){
                            $("#addForm select[name='department_hod_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='department_hod_final_remark']").prop('disabled', true);
                        }else if(roleName != "Department HOD"){
                            $("#addForm select[name='department_hod_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='department_hod_final_remark']").prop('disabled', true);
                        }


                        $("#addForm select[name='department_mca_second_status']").prop('disabled', false);
                        $("#addForm textarea[name='department_mca_second_remark']").prop('disabled', false);
                        $("#addForm select[name='department_mca_second_status']").val(data.audit.department_mca_second_status);
                        $("#addForm textarea[name='department_mca_second_remark']").val(data.audit.department_mca_second_remark);
                        if((data.audit.auditor_status == "1" || data.audit.auditor_status == "0")){
                            $("#addForm select[name='department_mca_second_status']").prop('disabled', true);
                            $("#addForm textarea[name='department_mca_second_remark']").prop('disabled', true);
                        }else if(roleName != "MCA"){
                            $("#addForm select[name='department_mca_second_status']").prop('disabled', true);
                            $("#addForm textarea[name='department_mca_second_remark']").prop('disabled', true);
                        }


                        $("#addForm select[name='auditor_status']").val(data.audit.auditor_status);
                        $("#addForm textarea[name='auditor_remark']").val(data.audit.auditor_remark);

                        if(data.audit.auditor_description != ""){
                            var url = "{{ route('view-objection-pdf', [':type', ':column', ':id']) }}";
                            url = url.replace(':type', 0)
                                    .replace(':column', 'auditor_description')
                                    .replace(':id', data.audit.id);

                            $('#auditorStatusDescription').find('.viewFile').attr('href', url);
                        }else{
                            $('#auditorStatusDescription').addClass('d-none');
                        }


                        $("#addForm input[name='completed_sub_unit']").val(data.audit.completed_sub_unit);
                        $("#addForm input[name='pending_sub_unit']").val(data.audit.pending_sub_unit);
                        auditorDescription.setData(data.audit.auditor_draft_description ?? '');
                        if(data.audit.dymca_final_status == "1" && roleName != "Auditor"){
                            $("#addForm select[name='auditor_status']").prop('disabled', true);
                            $("#addForm textarea[name='auditor_remark']").prop('disabled', true);
                            $("#addForm input[name='completed_sub_unit']").prop('disabled', true);
                            $("#addForm input[name='pending_sub_unit']").prop('disabled', true);
                        }else if(roleName != "Auditor"){
                            $("#addForm select[name='auditor_status']").prop('disabled', true);
                            $("#addForm textarea[name='auditor_remark']").prop('disabled', true);
                            $("#addForm input[name='completed_sub_unit']").prop('disabled', true);
                            $("#addForm input[name='pending_sub_unit']").prop('disabled', true);
                        }


                        $("#addForm select[name='dymca_final_status']").prop('disabled', false);
                        $("#addForm textarea[name='dymca_final_remark']").prop('disabled', false);
                        $("#addForm select[name='dymca_final_status']").val(data.audit.dymca_final_status);
                        $("#addForm textarea[name='dymca_final_remark']").val(data.audit.dymca_final_remark);
                        if(data.audit.mca_final_status == "1" || data.audit.mca_final_status == "0"){
                            $("#addForm select[name='dymca_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='dymca_final_remark']").prop('disabled', true);
                        }else if(roleName != "DY MCA"){
                            $("#addForm select[name='dymca_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='dymca_final_remark']").prop('disabled', true);
                        }


                        $("#addForm select[name='mca_final_status']").prop('disabled', false);
                        $("#addForm textarea[name='mca_final_remark']").prop('disabled', false);
                        $("#addForm select[name='mca_final_status']").val(data.audit.mca_final_status);
                        $("#addForm textarea[name='mca_final_remark']").val(data.audit.mca_final_remark);
                        if(data.audit.dymca_final_status != "1"){
                            $("#addForm select[name='mca_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='mca_final_remark']").prop('disabled', true);
                        }else if(roleName != "MCA"){
                            $("#addForm select[name='mca_final_status']").prop('disabled', true);
                            $("#addForm textarea[name='mca_final_remark']").prop('disabled', true);
                        }

                        // $('#mca_action_status').val(data.auditObjection.mca_action_status)
                        // $('#mca_remark').val(data.auditObjection.mca_remark)

                        @if(Auth::user()->hasRole('Department'))
                            if(data.audit.is_department_draft_save == "0" && data.audit.department_remark != null && data.audit.department_hod_final_status == "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('Department HOD'))
                            if(data.audit.department_hod_final_status == "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('MCA'))
                            if(data.audit.mca_final_status == "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('Auditor'))
                            if(data.audit.department_hod_final_status != "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('DY MCA'))
                            if(data.audit.dymca_final_status == "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @else
                            $('#saveObjectionStatus').removeClass('d-none');
                            $('#saveDraftObjectionStatus').removeClass('d-none');
                        @endif


                        $('#viewObjectionDetails').removeClass('d-none');
                        $('#viewFooterObjectionDetails').removeClass('d-none');

                        $("#addObjectionModal").modal("show");
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
