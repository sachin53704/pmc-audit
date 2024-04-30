<x-admin.layout>
    <x-slot name="title">Answered Questions</x-slot>
    <x-slot name="heading">Answered Questions</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row" id="editContainer" style="display:none;">
        <div class="col">
            <form class="form-horizontal form-bordered" method="post" id="editForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Department</h4>
                    </div>
                    <div class="card-body py-2">
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                        <div class="mb-3 row" id="objectionList">
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
                                    <th>Date</th>
                                    <th>File Description</th>
                                    <th>Remark</th>
                                    <th>View File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $audit->department?->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->date)->format('d m Y') }}</td>
                                        <td>{{ Str::limit($audit->description, '85') }}</td>
                                        <td>{{ Str::limit($audit->remark, '85') }}</td>
                                        <td>
                                            <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary edit-element px-2 py-1" title="View answered questions" data-id="{{ $audit->id }}"><i data-feather="file-text"></i> View Answers</button>
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



    @push('scripts')
        <!-- Edit -->
        <script>
            $("#buttons-datatables").on("click", ".edit-element", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                var url = "{{ route('compliance.info', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        editFormBehaviour();
                        if (!data.error)
                        {
                            $("#editForm input[name='edit_model_id']").val(data.audit.id);
                            $("#editForm #objectionList").html(data.innerHtml);
                        }
                        else
                        {
                            alert(data.error);
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        alert("Some thing went wrong");
                    },
                });
            });
        </script>


        <!-- Update Compliance -->
        <script>
            $(document).ready(function() {
                $("#editForm").submit(function(e) {
                    e.preventDefault();
                    $("#editSubmit").prop('disabled', true);
                    var formdata = new FormData(this);
                    formdata.append('_method', 'PUT');
                    var model_id = $('#edit_model_id').val();
                    var url = "{{ route('compliance.update', ":model_id") }}";
                    //
                    $.ajax({
                        url: url.replace(':model_id', model_id),
                        type: 'POST',
                        data: formdata,
                        contentType: false,
                        processData: false,
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
                        }
                    });

                });
            });
        </script>
    @endpush


</x-admin.layout>



