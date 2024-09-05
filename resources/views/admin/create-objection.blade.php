<x-admin.layout>
    <x-slot name="title">HMM</x-slot>
    <x-slot name="heading">HMM</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}
    <style>
        $('#addObjectionModal').modal({
            backdrop: 'static',
            keyboard: false
        })
    </style>




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
            <form action="" id="addForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Objection </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="audit_id" name="audit_id" value="">

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="objection_no">Auditor Para No <span class="text-danger">*</span></label>
                                <input type="text" name="objection_no" id="objection_no" class="form-control">
                            </div>

                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="entry_date">Entry Date <span class="text-danger">*</span></label>
                                <input type="date" name="entry_date" id="entry_date" class="form-control">
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="department_id">Department <span class="text-danger">*</span></label>
                                <select name="department_id" id="department_id" class="form-select">
                                    <option value="">Select department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                     
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="zone_id">Zone <span class="text-danger">*</span></label>
                                <select name="zone_id" id="zone_id" class="form-select">
                                    <option value="">Select zone</option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="from_year_id">From Year <span class="text-danger">*</span></label>
                                <select name="from_year_id" id="from_year_id" class="form-select">
                                    <option value="">Select from year</option>
                                    @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="to_year_id">To Year <span class="text-danger">*</span></label>
                                <select name="to_year_id" id="to_year_id" class="form-select">
                                    <option value="">Select to year</option>
                                    @foreach($fiscalYears as $fiscalYear)
                                    <option value="{{ $fiscalYear->id }}">{{ $fiscalYear->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="audit_type_id">Audit Type <span class="text-danger">*</span></label>
                                <select name="audit_type_id" id="audit_type_id" class="form-select">
                                    <option value="">Select audit type</option>
                                    @foreach($auditTypes as $auditType)
                                    <option value="{{ $auditType->id }}">{{ $auditType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="severity_id">Severity <span class="text-danger">*</span></label>
                                <select name="severity_id" id="severity_id" class="form-select">
                                    <option value="">Select severity</option>
                                    @foreach($severities as $severity)
                                    <option value="{{ $severity->id }}">{{ $severity->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="audit_para_id">Audit Para Category <span class="text-danger">*</span></label>
                                <select name="audit_para_id" id="audit_para_id" class="form-select">
                                    <option value="">Select option</option>
                                    @foreach($auditParaCategory as $auditParaCat)
                                    <option value="{{ $auditParaCat->id }}">{{ $auditParaCat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="text" name="amount" id="amount" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control">
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="work_name">Work Name</label>
                                <input type="text" name="work_name" id="work_name" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="contractor_name">Contractor Name</label>
                                <input type="text" name="contractor_name" id="contractor_name" class="form-control">
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="document">Upload Documents (docx, doc, xlsx, xls and pdf file only allow)</label>
                                <input type="file" name="document" id="document" class="form-control">
                            </div>
                        
                            <div class="col-lg-6 col-md-6 col-12 mb-3">
                                <label for="sub_unit">Sub Units <span class="text-danger">*</span></label>
                                <input type="text" name="sub_unit" id="sub_unit" class="form-control">
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
            var url = "{{ route('get-audit-info') }}";

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'audit_id': model_id,
                    'relations': "objections",
                },
                success: function(data, textStatus, jqXHR)
                {
                    if (!data.error)
                    {
                        $("#addObjectionModal #audit_id").val(data.audit.id);
                        $("#addObjectionModal #hmm_no").text(data.audit.audit_no);
                        $("#addObjectionModal [name='date']").val(data.audit.obj_date);
                        $("#addObjectionModal [name='subject']").val(data.audit.obj_subject);

                        $(".objSection").remove();
                        $("#objSection").html('<div class="objSection row mt-2"><input type="hidden" name="objection_no_0" value="1"><div class="col-3"><label class="form-label" >Question 1</label> <br></div><div class="col-sm-9"><textarea name="objection_0" id="objection_0" cols="10" rows="5" style="max-height: 100px; min-height:100px" class="form-control"></textarea><span class="text-danger is-invalid objection_0_err"></span></div></div>');
                        questionCounter = 1;

                        for(let i=0; i<data.audit.objections.length; i++)
                        {
                            let clonedSection = $(".objSection").first().clone();
                            questionCounter++;
                            clonedSection.find('.form-label').text('Question ' + questionCounter);
                            clonedSection.find('.text-danger').removeClass('objection_'+(questionCounter-2)+'_err');
                            clonedSection.find('.text-danger').addClass('objection_'+(questionCounter-1)+'_err');
                            clonedSection.find('input').attr('name', 'objection_no_'+(questionCounter-1)).attr('value', questionCounter);
                            clonedSection.find('textarea').attr('id', 'objection_'+(questionCounter-1));
                            clonedSection.find('textarea').attr('name', 'objection_'+(questionCounter-1));
                            $(".objSection").last().after(clonedSection);
                            $("#addObjectionModal [name='objection_"+(questionCounter-1)+"']").val("");
                            $("#addObjectionModal [name='objection_"+(questionCounter-2)+"']").val(data.audit.objections[i].objection);
                        }

                        $("#addObjectionModal").modal("show");
                    } else {
                        swal("Error!", data.error, "error");
                    }
                },
                error: function(error, jqXHR, textStatus, errorThrown) {
                    swal("Error!", "Some thing went wrong", "error");
                },
            });

            $('#assign-role-modal').modal('show');
        });

        $(".add-more").click(function(e){
            e.preventDefault();

            var clonedSection = $(".objSection").first().clone();
            questionCounter++;
            clonedSection.find('.form-label').text('Question ' + questionCounter);
            clonedSection.find('.text-danger').removeClass('objection_'+(questionCounter-2)+'_err');
            clonedSection.find('.text-danger').addClass('objection_'+(questionCounter-1)+'_err');
            clonedSection.find('input').attr('name', 'objection_no_'+(questionCounter-1)).attr('value', questionCounter);
            clonedSection.find('textarea').attr('id', 'objection_'+(questionCounter-1));
            clonedSection.find('textarea').attr('name', 'objection_'+(questionCounter-1));
            $(".objSection").last().after(clonedSection);
            $("#addObjectionModal [name='objection_"+(questionCounter-1)+"']").val("");

        });

        $(document).on("click", ".remove", function(){
            if($(".objSection").length > 1){
                $(".objSection").last().remove();
                questionCounter--;
            }
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
                success: function(data)
                {
                    $("#addObjectionSubmit").prop('disabled', false);
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
                        $("#addObjectionSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#addObjectionSubmit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                }
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
</script>

@endpush


</x-admin.layout>
