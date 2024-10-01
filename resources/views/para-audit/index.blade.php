<x-admin.layout>
    <x-slot name="title">Para Audit</x-slot>
    <x-slot name="heading">Para Audit</x-slot>
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
                                        <th>DYMCA Status</th>
                                        <th>MCA Status</th>
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
                                            {{-- <td>
                                                <span class="badge bg-secondary">{{ $audit->status_name }}</span>
                                            </td> --}}
                                            <td>
                                                <a href="{{ asset($audit->dl_file_path) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                            </td>
                                            <td>{{ Str::limit($audit->dl_description, '85') }}</td>
                                            <td>
                                                @if($audit->paraAudit?->dymca_status == 0)
                                                <span class="badge bg-danger">Rejected</span>
                                                @elseif($audit->paraAudit?->dymca_status == 1)
                                                <span class="badge bg-success">Approve</span>
                                                @else
                                                <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($audit->paraAudit?->mca_status == 0)
                                                <span class="badge bg-danger">Rejected</span>
                                                @elseif($audit->paraAudit?->mca_status == 1)
                                                <span class="badge bg-success">Approve</span>
                                                @else
                                                <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(($audit->paraAudit?->mca_status == 1 || $audit->paraAudit?->dymca_status == 1) && Auth::user()->hasRole('Auditor'))
                                                -
                                                @else
                                                @if($audit->paraAudit)
                                                <button class="btn btn-info edit-para-audit px-2 py-1" title="Edit Para Audit" data-controls-modal="editParaAuditModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}" data-para-audit="{{ $audit->paraAudit?->id }}">@if(Auth::user()->hasRole('Auditor'))Edit @else View @endif</button>
                                                @else
                                                <button class="btn btn-info add-para-audit px-2 py-1" title="Add Para Audit" data-controls-modal="addParaAuditModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}"><i data-feather="plus-circle"></i> Add Para Audit</button>
                                                @endif
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


        {{-- Add Para Audit Modal --}}
        <div class="modal fade" id="addParaAuditModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <form action="" id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Para Audit </h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="audit_id" value="" id="audit_id">
                            
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
                                <button class="btn btn-primary" id="addParaAuditSubmit" value="1" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        {{-- Edit Para Audit Modal --}}
        <div class="modal fade" id="editParaAuditModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <form action="" id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Para Audit </h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="" id="id">
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea type="text" name="description" id="editDescription" class="form-control"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="isDrafSave" value="" id="isEditDrafSave">
                            @if(Auth::user()->hasRole(['DY MCA', 'MCA']))
                            <div class="row">
                                @php 
                                    if(Auth::user()->hasRole('DY MCA')){
                                        $status = "dymca_status";
                                        $remark = "dymca_remark";
                                    }else{
                                        $status = "mca_status";
                                        $remark = "mca_remark";
                                    }
                                @endphp
                                <input type="hidden" name="statusApprove" value="1">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="{{ $status }}">Status</label>
                                    <select name="{{ $status }}" id="{{ $status }}" class="form-select" required>
                                        <option value="">Select Status</option>
                                        <option value="1">Approve</option>
                                        <option value="0">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <label for="{{ $remark }}">Remark</label>
                                    <textarea name="{{ $remark }}" id="{{ $remark }}" class="form-control"></textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <div class="hideFormSubmit">
                                @if(Auth::user()->hasRole('Auditor'))
                                <button class="btn btn-secondary" type="submit" value="1" id="editDraftSave">Draft Save</button>
                                @endif
                                <button class="btn btn-primary" id="editParaAuditSubmit" value="1" type="submit">Submit</button>
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

                editor.ui.view.editable.element.style.height = '200px';  // Fixed height

                // Make the editor scrollable
                editor.ui.view.editable.element.style.overflowY = 'auto';
            })
            .catch(error => {
                console.error('Error during initialization of the editor', error);
            });
    </script>

<script>
        
    // Initialize CKEditor
    let editorEditInstance;
    ClassicEditor
        .create(document.querySelector('#editDescription'),{
            toolbar: {
                shouldNotGroupWhenFull: true
            }
        })
        .then(editor => {
            editorEditInstance = editor;
            @if(Auth::user()->hasRole(['DY MCA', 'MCA']))
            editorEditInstance.enableReadOnlyMode('reason');
            @endif

            editor.ui.view.editable.element.style.height = '200px';  // Fixed height

            // Make the editor scrollable
            editor.ui.view.editable.element.style.overflowY = 'auto';
        })
        .catch(error => {
            console.error('Error during initialization of the editor', error);
        });
</script>




    {{-- Open modal and Add more --}}
    <script>
        var questionCounter = 1;

        $("#buttons-datatables").on("click", ".add-para-audit", function(e) {
            e.preventDefault();
            var url = "{{ route('para-audit.create') }}";
            var model_id = $(this).attr("data-id");

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    'audit_id': model_id,
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
                        $('#audit_id').val(data.id)
                        editorInstance.setData(data.html);
                        $("#addParaAuditModal").modal("show");
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
        $('#addParaAuditSubmit').click(function(){
            $('#isDrafSave').val(0)
        });

        // Submit Objection Form
        $("#addForm").submit(function(e) {
            e.preventDefault();

            var formdata = new FormData(this);

            $.ajax({
                url: '{{ route('para-audit.store') }}',
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
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                },
                complete: function() {
                    $('#preloader').css('opacity', '0');
                    $('#preloader').css('visibility', 'hidden');
                },
            });

        });



        $("#buttons-datatables").on("click", ".edit-para-audit", function(e) {
            e.preventDefault();
            var model_id = $(this).attr("data-id");
            var url = "{{ route('para-audit.edit', ":model_id") }}";
            var paraAuditId = $(this).attr("data-para-audit");
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'GET',
                data: {
                    'audit_id': model_id,
                    'id': paraAuditId
                },
                beforeSend: function()
                {
                    $('#preloader').css('opacity', '0.5');
                    $('#preloader').css('visibility', 'visible');
                    $("#editParaAuditModal").modal("show");
                },
                success: function(data, textStatus, jqXHR)
                {
                    if (!data.error)
                    {
                        $("#editForm input[name='id']").val(data.audit.id)
                        editorEditInstance.setData(data.audit.description);
                        // $("#addParaAuditModal").modal("show");
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

        $('#editDraftSave').click(function(){
            $('#isEditDrafSave').val(1)
        });
        $('#editParaAuditSubmit').click(function(){
            $('#isEditDrafSave').val(0)
        });




        // Submit Objection Form
        $("#editForm").submit(function(e) {
            e.preventDefault();

            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');

            $.ajax({
                url: '{{ route('para-audit.update', '1') }}',
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
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
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
