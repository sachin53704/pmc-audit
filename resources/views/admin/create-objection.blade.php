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
                                                <button class="btn btn-info add-objection px-2 py-1" title="Add Objection" data-id="{{ $audit->id }}"><i data-feather="plus-circle"></i> Add Objection</button>
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    {{-- Send Department Letter Modal --}}
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

                        <div class="mb-1 row">
                            <label class="col-sm-3 col-form-label" for="hmm_no">HMM No : </label>
                            <div class="col-sm-9">
                                <h6 id="hmm_no" class="pt-2"></h6>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="date">Date </label>
                            <div class="col-sm-9">
                                <input type="date" name="date" readonly class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                                <span class="text-danger is-invalid date_err"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="subject">Subject </label>
                            <div class="col-sm-9">
                                <input type="text" name="subject" class="form-control" >
                                <span class="text-danger is-invalid subject_err"></span>
                            </div>
                        </div>

                        <hr class="mt-2 mb-1">
                        <label class="col-12 col-form-label py-0" for="objection">Objections </label>
                        <hr class="mt-1 mb-2">

                        <div class="mb-3">
                            <div class="objSection row mt-2">
                                <input type="hidden" name="objection_no_0" value="1">
                                <div class="col-3">
                                    <label class="form-label" >Question 1</label> <br>
                                </div>
                                <div class="col-sm-9">
                                    <textarea name="objection_0" id="objection_0" cols="10" rows="5" style="max-height: 100px; min-height:100px" class="form-control"></textarea>
                                    <span class="text-danger is-invalid objection_0_err"></span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger remove float-start w-25 mt-2 mx-1">Remove</button>
                            <button type="button" class="btn btn-primary add-more w-25 float-end mt-2 mx-1">Add More</button>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
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

@endpush


</x-admin.layout>
