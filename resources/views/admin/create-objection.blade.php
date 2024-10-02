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
                                            <td><span style="cursor: pointer" title="{{ $audit->description }}">{{ Str::limit($audit->description, '30') }}</span></td>
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
        <div class="modal-dialog modal-xl" role="document">
            <form action="" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Objection </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modelObjectionId"></div>
                        <hr>
                        <input type="hidden" name="audit_id" value="" id="audit_id">
                        <input type="hidden" name="audit_objection_id" value="" id="audit_objection_id">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="objection_no">HMM No. <span class="text-danger">*</span></label>
                                <input type="text" name="objection_no" id="objection_no" class="form-control" value="{{ time() }}">
                                <span class="text-danger is-invalid objection_no_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                <input type="date" name="entry_date" id="entry_date" class="form-control">
                                <span class="text-danger is-invalid entry_date_err"></span>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="department_id">Department <span class="text-danger">*</span></label>
                                <input type="hidden" name="department_id" id="department_hidden_id">
                                <input type="text" name="department_name_id" disabled id="department_name_id" class="form-control" />
                                <span class="text-danger is-invalid department_id_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="zone_id">Zone <span class="text-danger">*</span></label>
                                <select name="zone_id" id="zone_id" class="form-select">
                                    <option value="">Select zone</option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid zone_id_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="from_year">From Year <span class="text-danger">*</span></label>
                                <select name="from_year" id="from_year" class="form-select">
                                    <option value="">Select from year</option>
                                    @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid from_year_err"></span>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="to_year">To Year <span class="text-danger">*</span></label>
                                <select name="to_year" id="to_year" class="form-select">
                                    <option value="">Select to year</option>
                                    @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid to_year_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="audit_type_id">Audit Type <span class="text-danger">*</span></label>
                                <select name="audit_type_id" id="audit_type_id" class="form-select">
                                    <option value="">Select audit type</option>
                                    @foreach($auditTypes as $auditType)
                                    <option value="{{ $auditType->id }}">{{ $auditType->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid audit_type_id_err"></span>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="severity_id">Severity <span class="text-danger">*</span></label>
                                <select name="severity_id" id="severity_id" class="form-select">
                                    <option value="">Select severity</option>
                                    @foreach($severities as $severity)
                                    <option value="{{ $severity->id }}">{{ $severity->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger is-invalid severity_id_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
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
                            <div class="col-lg-4 col-md-6 col-12 mb-3 d-none isAmountDisplayOrNot">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="text" name="amount" id="amount" class="form-control">
                                <span class="text-danger is-invalid amount_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control">
                                <span class="text-danger is-invalid subject_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="documents">Upload Documents (docx, doc, xlsx, xls and pdf file only allow)</label>
                                <input type="file" name="documents" id="documents" class="form-control">
                                <span class="text-danger is-invalid documents_err"></span>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
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
                        <input type="hidden" name="isDrafSave" value="" id="isDrafSave">


                    </div>
                    <div class="modal-footer">
                        <div class="hideFormSubmit">
                            <button class="btn btn-secondary" type="submit" value="1" id="draftSave">Draft Save</button>
                            <button class="btn btn-primary" id="addObjectionSubmit" value="1" type="submit">Submit</button>
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
                    $("#addForm input[name='audit_objection_id']").val(data.auditObjection.id);
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
                    // if(data.auditObjection.document && data.auditObjection.document != ""){
                    //     var file = "{{ asset('storage') }}/"+data.auditObjection.document;
                    // }else{
                    //     var file = "javascript:void(0)";
                    // }
                    // $("#addForm #documentFile").attr('href', file);
                    $("#addForm input[name='sub_unit']").val(data.auditObjection.sub_unit);
                    // $("#addForm textarea[name='description']").val(data.auditObjection.desc
                    editorInstance.setData(data.auditObjection.draft_description);

                    // if((data.auditObjection.dymca_status != "1")){
                    //     $('.hideFormSubmit').removeClass('d-none')
                    // }else if((data.auditObjection.mca_status == "2")){
                    //     $('.hideFormSubmit').removeClass('d-none')
                    // }else{
                    //     $('.hideFormSubmit').addClass('d-none')
                    // }
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

        $('#draftSave').click(function(){
            $('#isDrafSave').val(1)
        });
        $('#addObjectionSubmit').click(function(){
            $('#isDrafSave').val(0)
        });

        // Submit Objection Form
        $("#addForm").submit(function(e) {
            e.preventDefault();
            $("#addObjectionSubmit").prop('disabled', true);

            var formdata = new FormData(this);

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

@endpush


</x-admin.layout>
