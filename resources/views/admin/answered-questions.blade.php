<x-admin.layout>
    <x-slot name="title">Compliance Objections</x-slot>
    <x-slot name="heading">Compliance Objections</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


   


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>HMM No.</th>
                                    <th>Subject</th>
                                    <th>Entry Date</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $audit->department?->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->audit->date)->format('d-m-Y') }}</td>
                                        <td>{{ $audit->objection_no }}</td>
                                        <td>{{ $audit->subject }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->entry_date)->format('d-m-Y') }}</td>
                                        <td>@if($audit->audit?->description) <span style="cursor: pointer" title="{{ $audit->audit?->description }}">{{ Str::limit($audit->audit?->description, '30') }}</span>@else - @endif</td>
                                        <td>
                                            <button class="btn btn-secondary viewObjection px-2 py-1" title="View compliance objection" data-controls-modal="addObjectionModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}"><i data-feather="file-text"></i> View Compliance</button>
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

                        <div>
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
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#departmentCompliance" aria-expanded="false" aria-controls="departmentCompliance">
                                                                Department Compliance 
                                                            </button>
                                                        </h2>
                                                        <div id="departmentCompliance" class="accordion-collapse collapse" aria-labelledby="accordionwithplusExample1" data-bs-parent="#accordionWithplusicon">
                                                            
                                                            <div class="row px-3 py-2">
                                                                <div class="col-6 mb-3">
                                                                    <label for="department_file">Compliance File <span class="text-danger">*</span></label>
                                                                    <a href="#" class="btn btn-primary d-none complianceFile" target="_blank">View File</a>
                                                                    @if(Auth::user()->hasRole('Department'))
                                                                    <input type="file" name="department_files" id="department_file" class="form-control">
                                                                    @endif
                                                                    <span class="text-danger is-invalid department_file_err"></span>
                                                                </div>
                                                                <div class="col-6 mb-3">
                                                                    <label for="department_file">Submitted Compliance <span class="text-danger">*</span></label>
                                                                    <input type="text" id="submit_compliance" class="form-control" disabled>
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <div class="d-flex justify-content-between">
                                                                        <label for="department_remark">Compliance Description <span class="text-danger">*</span></label>

                                                                        <div id="departmentCoveringLetter">
                                                                            <a href="#" class="btn btn-primary btn-sm coveringLetter" target="_blank">Covering letter</a>
                                                                            <a href="#" class="btn btn-primary btn-sm viewFile" target="_blank">View Details</a>
                                                                        </div>
                                                                    </div>
                                                                    <textarea name="department_remark" id="department_remark" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid department_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample2">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#departmentHodStatus" aria-expanded="false" aria-controls="departmentHodStatus">
                                                                Department HOD Status
                                                            </button>
                                                        </h2>
                                                        <div id="departmentHodStatus" class="accordion-collapse collapse" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                    
                                                                <div class="col-6">
                                                                    <label for="department_hod_final_status">Department HOD Status</label>
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
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mcaForwardToAuditor" aria-expanded="false" aria-controls="mcaForwardToAuditor">
                                                                MCA Forward To Auditor
                                                            </button>
                                                        </h2>
                                                        <div id="mcaForwardToAuditor" class="accordion-collapse collapse" aria-labelledby="accordionwithplusExample3" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                    
                                                                <div class="col-6">
                                                                    <label for="department_mca_second_status">MCA Status</label>
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
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#auditorStatus" aria-expanded="true" aria-controls="auditorStatus">
                                                                Auditor Status
                                                            </button>
                                                        </h2>
                                                        <div id="auditorStatus" class="accordion-collapse collapse show" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                
                                                                <input type="hidden" name="is_draft_save" id="is_draft_save">
                                                                <div class="col-12 mb-3">

                                                                    <div class="d-flex justify-content-between">
                                                                        <label for="department_remark">Description <span class="text-danger">*</span></label>

                                                                        <div id="auditorStatusDescription">
                                                                            <a href="#" class="btn btn-primary btn-sm viewFile" target="_blank">View Details</a>
                                                                        </div>
                                                                    </div>
                                                                    <textarea name="auditor_description" id="auditor_description" class="form-control"></textarea>
                                                                    <span class="text-danger is-invalid auditor_description_err"></span>
                                                                </div>

                                                                <div class="col-3 px-3 pt-2">
                                                                    <label for="auditor_status">Completed Objection <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" name="completed_sub_unit" required>
                                                                    <span class="text-danger is-invalid completed_sub_unit_err"></span>
                                                                </div>

                                                                <div class="col-3 px-3 pt-2">
                                                                    <label for="auditor_status">Pending Objection <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" name="pending_sub_unit" required>
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
                                                                    <textarea name="auditor_remark" class="form-control" required></textarea>
                                                                    <span class="text-danger is-invalid auditor_remark_err"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="accordionwithplusExample2">
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dymcaStatus" aria-expanded="false" aria-controls="dymcaStatus">
                                                                DyMca Status
                                                            </button>
                                                        </h2>
                                                        <div id="dymcaStatus" class="accordion-collapse collapse" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                
                                                                <div class="col-6">
                                                                    <label for="dymca_final_status">Dymca Status</label>
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
                                                            <button style="font-size: 18px;font-weight: 600;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mcaStatus" aria-expanded="false" aria-controls="mcaStatus">
                                                                MCA Status
                                                            </button>
                                                        </h2>
                                                        <div id="mcaStatus" class="accordion-collapse collapse" aria-labelledby="accordionwithplusExample2" data-bs-parent="#accordionWithplusicon">
                                                            <div class="row px-3 py-2">
                                                                
                                                                <div class="col-6">
                                                                    <label for="mca_final_status">MCA Status</label>
                                                                    <select name="mca_final_status" class="form-select">
                                                                        <option value="">Select Status</option>
                                                                        <option value="1">Approve</option>
                                                                        <option value="0">Forward to department</option>
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
                        <button class="btn btn-warning" id="saveDraftObjectionStatus" type="submit">Draft Save</button>
                        <button class="btn btn-primary" id="saveObjectionStatus" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    @push('scripts')

        <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
        

        <script>
            $(document).ready(function(){
                $('#saveObjectionStatus').click(function(){
                    $('#is_draft_save').val(0);
                });

                $('#saveDraftObjectionStatus').click(function(){
                    $('#is_draft_save').val(1);
                });
            })
            // Initialize CKEditor
            let editorInstance;
            ClassicEditor
                .create(document.querySelector('#description'),{
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
                        deditorInstance.enableReadOnlyMode('reason');
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
                        editor.ui.view.editable.element.style.height = '200px';  // Fixed height

                        // Make the editor scrollable
                        editor.ui.view.editable.element.style.overflowY = 'auto';
                    })
                    .catch(error => {
                        console.error('Error during initialization of the editor', error);
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
                        $('#saveObjectionStatus').removeClass('d-none');
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
                        


                        let roleName = "{{ Auth::user()->roles[0]->name }}";
                        
                        // department status   
                        deditorInstance.setData("");                     
                        if(data.auditObjection.department_draft_remark){
                            deditorInstance.setData(data.auditObjection.department_draft_remark);
                        }
                        if(data.auditObjection.department_file != ""){
                            $('.complianceFile').removeClass('d-none');
                            $('.complianceFile').prop('href', "{{ asset('storage') }}/"+data.auditObjection.department_file);
                        }
                        $('#submit_compliance').val(data.auditObjection.submit_compliance);
                        
                        if(data.auditObjection.department_hod_final_status == "1" || data.auditObjection.mca_final_status != "0"){
                            $('.complianceFile').prop('disabled', true)
                        }

                        if(data.auditObjection.department_remark != "" && data.auditObjection.department_letter){
                            $('#departmentCoveringLetter').removeClass('d-none');
                            $('#departmentCoveringLetter').find('.coveringLetter').attr('href', "{{ asset('storage') }}/"+data.auditObjection.department_letter);

                            var url = "{{ route('view-objection-pdf', [':type', ':column', ':id']) }}";
                            url = url.replace(':type', 1)
                                    .replace(':column', 'department_remark')
                                    .replace(':id', data.auditObjection.id);

                            $('#departmentCoveringLetter').find('.viewFile').attr('href', url);
                        }else{
                            $('#departmentCoveringLetter').addClass('d-none');
                        }
                        

                        
                        $("#addForm select[name='department_hod_final_status']").val(data.auditObjection.department_hod_final_status);
                        $("#addForm textarea[name='department_hod_final_remark']").val(data.auditObjection.department_hod_final_remark);
                        if((data.auditObjection.department_mca_second_status == "1" && data.auditObjection.department_draft_remark != "")){
                            $("#addForm select[name='department_hod_final_status']").prop('disabled', true)
                            $("#addForm textarea[name='department_hod_final_remark']").prop('disabled', true)
                        }else if(roleName != "Department HOD"){
                            $("#addForm select[name='department_hod_final_status']").prop('disabled', true)
                            $("#addForm textarea[name='department_hod_final_remark']").prop('disabled', true)
                        }


                        $("#addForm select[name='department_mca_second_status']").val(data.auditObjection.department_mca_second_status);
                        $("#addForm textarea[name='department_mca_second_remark']").val(data.auditObjection.department_mca_second_remark);
                        if((data.auditObjection.auditor_status == "1" || data.auditObjection.auditor_status == "0")){
                            $("#addForm select[name='department_mca_second_status']").prop('disabled', true)
                            $("#addForm textarea[name='department_mca_second_remark']").prop('disabled', true)
                        }else if(roleName != "MCA"){
                            $("#addForm select[name='department_mca_second_status']").prop('disabled', true)
                            $("#addForm textarea[name='department_mca_second_remark']").prop('disabled', true)
                        }


                        $("#addForm select[name='auditor_status']").val(data.auditObjection.auditor_status);
                        $("#addForm textarea[name='auditor_remark']").val(data.auditObjection.auditor_remark);

                        if(data.auditObjection.auditor_description != ""){
                            var url = "{{ route('view-objection-pdf', [':type', ':column', ':id']) }}";
                            url = url.replace(':type', 1)
                                    .replace(':column', 'auditor_description')
                                    .replace(':id', data.auditObjection.id);

                            $('#auditorStatusDescription').find('.viewFile').attr('href', url);
                        }else{
                            $('#auditorStatusDescription').addClass('d-none');
                        }


                        $("#addForm input[name='completed_sub_unit']").val(data.auditObjection.completed_sub_unit);
                        $("#addForm input[name='pending_sub_unit']").val(data.auditObjection.pending_sub_unit);
                        auditorDescription.setData(data.auditObjection.auditor_draft_description ?? '');
                        if(data.auditObjection.dymca_final_status == "1"){
                            $("#addForm select[name='auditor_status']").prop('disabled', true);
                            $("#addForm textarea[name='auditor_remark']").prop('disabled', true);
                            $("#addForm input[name='completed_sub_unit']").prop('disabled', true);
                            $("#addForm input[name='pending_sub_unit']").prop('disabled', true);
                            $('#saveDraftObjectionStatus').addClass('d-none');
                            $('#saveObjectionStatus').addClass('d-none');
                        }else if(roleName != "Auditor"){
                            $("#addForm select[name='auditor_status']").prop('disabled', true);
                            $("#addForm textarea[name='auditor_remark']").prop('disabled', true);
                            $("#addForm input[name='completed_sub_unit']").prop('disabled', true);
                            $("#addForm input[name='pending_sub_unit']").prop('disabled', true);
                            $('#saveDraftObjectionStatus').addClass('d-none');
                            $('#saveObjectionStatus').addClass('d-none');
                        }else{
                            $('#saveDraftObjectionStatus').removeClass('d-none');
                            $('#saveObjectionStatus').removeClass('d-none');
                        }

                        $("#addForm select[name='dymca_final_status']").val(data.auditObjection.dymca_final_status);
                        $("#addForm textarea[name='dymca_final_remark']").val(data.auditObjection.dymca_final_remark);
                        if(data.auditObjection.mca_final_status == "1" || data.auditObjection.mca_final_status == "0"){
                            $("#addForm select[name='dymca_final_status']").prop('disabled', true)
                            $("#addForm textarea[name='dymca_final_remark']").prop('disabled', true)
                        }else if(roleName != "DY MCA"){
                            $("#addForm select[name='dymca_final_status']").prop('disabled', true)
                            $("#addForm textarea[name='dymca_final_remark']").prop('disabled', true)
                        }


                        $("#addForm select[name='mca_final_status']").val(data.auditObjection.mca_final_status);
                        $("#addForm textarea[name='mca_final_remark']").val(data.auditObjection.mca_final_remark);
                        if(data.auditObjection.dymca_final_status != "1"){
                            $("#addForm select[name='mca_final_status']").prop('disabled', true)
                            $("#addForm textarea[name='mca_final_remark']").prop('disabled', true)
                        }else if(roleName != "MCA"){
                            $("#addForm select[name='mca_final_status']").prop('disabled', true)
                            $("#addForm textarea[name='mca_final_remark']").prop('disabled', true)
                        }
                        // $('#mca_action_status').val(data.auditObjection.mca_action_status)
                        // $('#mca_remark').val(data.auditObjection.mca_remark)
                        if((data.auditObjection.department_hod_final_status == "1" && data.auditObjection.mca_final_status != "0") && roleName == "Auditor"){
                            // $('#viewFooterObjectionDetails button').addClass('d-none');
                        }

                        @if(Auth::user()->hasRole('Department'))
                            if(data.auditObjection.is_department_draft_save == "0" && data.auditObjection.department_remark != null){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('Department HOD'))
                            if(data.auditObjection.department_hod_final_status == "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('MCA'))
                            if(data.auditObjection.mca_final_status == "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('Auditor'))
                            if(data.auditObjection.department_hod_final_status != "1"){
                                $('#saveObjectionStatus').addClass('d-none');
                                $('#saveDraftObjectionStatus').addClass('d-none');
                            }
                        @elseif(Auth::user()->hasRole('DY MCA'))
                            if(data.auditObjection.dymca_final_status == "1"){
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
        </script>
    @endpush


</x-admin.layout>



