<x-admin.layout>
    <x-slot name="title">Send HMM</x-slot>
    <x-slot name="heading">Send HMM</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

    <form>
        <div class="card">
            <div class="card-header">
                <h3>Department</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="department">Select Department</label>
                            <select name="department" class="form-select" id="department" required>
                                <option value="">Select</option>
                                @foreach($departments as $department)
                                <option @if(isset(request()->department) && request()->department == $department->id)selected @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <button class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(isset(request()->department))
    <div class="row">
        <div class="col-lg-12">
            <form action="" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="modal-title">Send HMM </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
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
                                    @forelse($audits as $audit)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="audit_id" value="{{ $audit->audit_id }}" >
                                                <input type="checkbox" class="form-check-input" name="id[]" value="{{ $audit->id }}" style="font-size: 15px;">
                                            </td>
                                            <td>{{ $audit->department?->name }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->audit?->date)->format('d-m-Y') }}</td>
                                            <td>{{ $audit->objection_no }}</td>
                                            <td>{{ $audit->subject }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->entry_date)->format('d-m-Y') }}</td>
                                            <td>@if($audit->audit?->description) <span style="cursor: pointer" title="{{ $audit->audit?->description }}">{{ Str::limit($audit->audit?->description, '30') }}</span>@else - @endif</td>
                                            <td>
                                                <button type="button" data-id="{{ $audit->id }}" class="btn btn-primary viewObjection btn-sm">View Objection</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" align="center"><h4>No Data Found</h4></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(count($audits) > 0)
                    <div class="card-footer">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-primary" type="submit" style="padding: 6px 8px 6px 10px">
                            <i data-feather="send"></i> Send HMM Draft
                        </button>
                    </div>
                    @endif
                </div>

            </form>
        </div>
    </div>
    @endif



    {{-- Add Objection Modal --}}
    <div class="modal fade" id="addObjectionModal" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form action="" id="viewForm" enctype="multipart/form-data">
               
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Objection </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="objection_no">Hmm No.</label>
                                <input type="text" name="objection_no" id="objection_no" class="form-control" readonly style="background: #fff;color:#000">
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="entry_date">Entry Date</label>
                                <input type="text" readonly id="entry_date" name="entry_date" class="form-control" style="background: #fff;color:#000">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="department">Department</label>
                                <input type="text" readonly id="department" name="department" class="form-control" style="background: #fff;color:#000">
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="zone">Zone</label>
                                <input type="text" readonly id="zone" name="zone" class="form-control" style="background: #fff;color:#000">
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="from_year">Financial From Year</label>
                                <input type="text" readonly id="from_year" name="from_year" class="form-control" style="background: #fff;color:#000">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="to_year">Financial To Year</label>
                                <input type="text" readonly id="to_year" name="to_year" class="form-control" style="background: #fff;color:#000">
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="audit_para_type">Audit Para Type</label>
                                <input type="text" readonly id="audit_para_type" name="audit_para_type" class="form-control" style="background: #fff;color:#000">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="severity">Severity</label>
                                <input type="text" readonly id="severity" name="severity" class="form-control" style="background: #fff;color:#000">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="audit_para_category">Audit para Category</label>
                                <input type="text" readonly id="Audit para Category" name="audit_para_category" class="form-control" style="background: #fff;color:#000">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3 d-none isAmountDisplayOrNot">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" readonly id="amount" class="form-control" style="background: #fff;color:#000">
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="subject">Subject</label>
                                <input type="text" name="subject" readonly id="subject" class="form-control" style="background: #fff;color:#000">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <a href="#" id="documentFile" target="_blank" class="btn btn-primary mt-4">View File</a>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <label for="sub_unit">No of Objection</label>
                                <input type="number" name="sub_unit" disabled id="sub_unit" class="form-control" style="background: #fff;color:#000">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="d-flex justify-content-between">
                                    <label for="description">Objection Description</label>
                                    <a href="#" target="_blank" class="btn btn-primary btn-sm viewObjectionDescription">View Details</a>
                                </div>
                                <textarea type="text" name="description" id="description" class="form-control" disabled></textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>


</x-admin.layout>


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
            editor.ui.view.editable.element.style.height = '150px';  // Fixed height

            // Make the editor scrollable
            editor.ui.view.editable.element.style.overflowY = 'auto';
        })
        .catch(error => {
            console.error('Error during initialization of the editor', error);
        });
</script>

<script>
    $('body').on('click', '.viewObjection', function(){
        let id = $(this).attr('data-id');

        $.ajax({
            url: "{{ route('hmm-draft-view-objection') }}",
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
                $("#viewForm input[name='objection_no']").val(data.auditObjection.objection_no);
                $("#viewForm input[name='entry_date']").val(data.auditObjection.entry_date);
                $("#viewForm input[name='department']").val(data.auditObjection?.department?.name);
                $("#viewForm input[name='zone']").val(data.auditObjection?.zone?.name);
                $("#viewForm input[name='from_year']").val(data.auditObjection?.from?.name);
                $("#viewForm input[name='to_year']").val(data.auditObjection?.to?.name);
                $("#viewForm input[name='audit_para_type']").val(data.auditObjection?.audit_type?.name);
                $("#viewForm input[name='severity']").val(data.auditObjection?.severity?.name);
                $("#viewForm input[name='audit_para_category']").val(data.auditObjection?.audit_para_category?.name);
                if(data.auditObjection.amount > 0){
                    $('.isAmountDisplayOrNot').removeClass('d-none');
                }else{
                    $('.isAmountDisplayOrNot').addClass('d-none');
                }
                $("#viewForm input[name='amount']").val(data.auditObjection.amount);
                $("#viewForm input[name='subject']").val(data.auditObjection.subject);
                if(data.auditObjection.document && data.auditObjection.document != ""){
                    var file = "{{ asset('storage') }}/"+data.auditObjection.document;
                }else{
                    var file = "javascript:void(0)";
                }
                $("#viewForm #documentFile").attr('href', file);
                $("#viewForm input[name='sub_unit']").val(data.auditObjection.sub_unit);
                editorInstance.setData(data.auditObjection.description);

                if(data.auditObjection.description != ""){
                    var url = "{{ route('view-objection-pdf', [':type', ':column', ':id']) }}";
                    url = url.replace(':type', 1)
                            .replace(':column', 'description')
                            .replace(':id', data.auditObjection.id);
                    $('.viewObjectionDescription').attr('href', url);
                }

                $('#addObjectionModal').modal("show")
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
    // Submit Objection Form
    $("#addForm").submit(function(e) {
            e.preventDefault();

            var formdata = new FormData(this);

            $.ajax({
                url: '{{ route('objection.store-not-send-hmm-draft') }}',
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