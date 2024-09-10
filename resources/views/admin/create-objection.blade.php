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
                                        <th>View File</th>
                                        <th>Status</th>
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
                                            <td>{{ Str::limit($audit->description, '85') }}</td>
                                            <td>{{ Str::limit($audit->remark, '85') }}</td>
                                            <td>
                                                <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $audit->status_name }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ asset($audit->dl_file_path) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                            </td>
                                            <td>{{ Str::limit($audit->dl_description, '85') }}</td>
                                            <td>
                                                <button class="btn btn-info add-objection px-2 py-1" title="Add Objection" data-controls-modal="addObjectionModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}"><i data-feather="plus-circle"></i> Add Objection</button>
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
        <div class="modal-dialog modal-lg" role="document">
            <form action="" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Objection </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modelObjectionId"></div>

                        <input type="hidden" name="audit_id" value="" id="audit_id">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="objection_no">Auditor Para No <span class="text-danger">*</span></label>
                                <input type="text" name="objection_no" id="objection_no" class="form-control" value="{{ time() }}">
                                <span class="text-danger is-invalid objection_no_err"></span>
                            </div>

                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                <input type="date" name="entry_date" id="entry_date" class="form-control">
                                <span class="text-danger is-invalid entry_date_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="department_id">Department <span class="text-danger">*</span></label>
                                <input type="hidden" name="department_id" id="department_hidden_id">
                                <input type="text" name="department_name_id" disabled id="department_name_id" class="form-control" />
                                <span class="text-danger is-invalid department_id_err"></span>
                            </div>
                     
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="zone_id">Zone <span class="text-danger">*</span></label>
                                <select name="zone_id" id="zone_id" class="form-select">
                                    <option value="">Select zone</option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid zone_id_err"></span>
                            </div>
                        
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="from_year">From Year <span class="text-danger">*</span></label>
                                <select name="from_year" id="from_year" class="form-select">
                                    <option value="">Select from year</option>
                                    @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid from_year_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="to_year">To Year <span class="text-danger">*</span></label>
                                <select name="to_year" id="to_year" class="form-select">
                                    <option value="">Select to year</option>
                                    @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid to_year_err"></span>
                            </div>
                        
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="audit_type_id">Audit Type <span class="text-danger">*</span></label>
                                <select name="audit_type_id" id="audit_type_id" class="form-select">
                                    <option value="">Select audit type</option>
                                    @foreach($auditTypes as $auditType)
                                    <option value="{{ $auditType->id }}">{{ $auditType->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid audit_type_id_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="severity_id">Severity <span class="text-danger">*</span></label>
                                <select name="severity_id" id="severity_id" class="form-select">
                                    <option value="">Select severity</option>
                                    @foreach($severities as $severity)
                                    <option value="{{ $severity->id }}">{{ $severity->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid severity_id_err"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="audit_para_category_id">Audit Para Category <span class="text-danger">*</span></label>
                                <input type="hidden" name="audit_para_value" id="auditParaValue">
                                <select name="audit_para_category_id" id="audit_para_category_id" class="form-select">
                                    <option data-amount="" value="">Select option</option>
                                    @foreach($auditParaCategory as $auditParaCat)
                                    <option data-amount="{{ $auditParaCat->is_amount }}" value="{{ $auditParaCat->id }}">{{ $auditParaCat->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid audit_para_category_id_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3 d-none isAmountDisplayOrNot">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="text" name="amount" id="amount" class="form-control">
                                <span class="text-danger is-invalid amount_err"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control">
                                <span class="text-danger is-invalid subject_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="work_name">Work Name</label>
                                <input type="text" name="work_name" id="work_name" class="form-control">
                                <span class="text-danger is-invalid work_name_err"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="contractor_name">Contractor Name</label>
                                <input type="text" name="contractor_name" id="contractor_name" class="form-control">
                                <span class="text-danger is-invalid contractor_name_err"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="documents">Upload Documents (docx, doc, xlsx, xls and pdf file only allow)</label>
                                <input type="file" name="documents" id="documents" class="form-control">
                                <span class="text-danger is-invalid documents_err"></span>
                            </div>
                        
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="sub_unit">Sub Units <span class="text-danger">*</span></label>
                                <input type="text" name="sub_unit" id="sub_unit" class="form-control">
                                <span class="text-danger is-invalid sub_unit_err"></span>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" id="description" class="form-control"></textarea>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary close-modal" data-bs-dismiss="modal" type="button" >Cancel</button>
                        <button class="btn btn-primary" id="addObjectionSubmit" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    

@push('scripts')

    {{-- Open modal and Add more --}}
    <script>
        var questionCounter = 1;

        $("#buttons-datatables").on("click", ".add-objection", function(e) {
            e.preventDefault();
            var model_id = $(this).attr("data-id");
            $('#audit_id').val(model_id)
            var url = "{{ route('objection.get-assign-objection') }}";

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'audit_id': model_id,
                    'relations': "objections",
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
                        $('#modelObjectionId').html(data.objectionHtml)
                        $('#department_name_id').val(data.departmentName);
                        $('#department_hidden_id').val(data.department)

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
            formdata.append('question_count', questionCounter);


            $.ajax({
                url: '{{ route('objection.store') }}',
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

<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

<script>
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('textarea'))
        .then(editor => {
            console.log('Editor was initialized', editor);
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

@endpush


</x-admin.layout>
