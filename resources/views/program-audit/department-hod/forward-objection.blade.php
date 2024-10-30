<x-admin.layout>
    <x-slot name="title">HMM</x-slot>
    <x-slot name="heading">HMM</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

        <div class="row">
            <div class="col-lg-12">
                <form action="" id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="modal-title">Forward HMM To Department</h5>
                            @php $data = []; @endphp
                            @foreach($audits as $audit)
                            @php array_push($data, $audit->id) @endphp
                            @endforeach
                            <div>
                                <a href="{{ route('objection.view-forward-objection-to-department', ['id' => $data]) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                <a href="{{ route('objection.view-forward-objection-to-department', ['id' => $data]) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                            </div>
                        </div>
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
                                <table class="table table-bordered nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="form-check-input" name="id[]" style="font-size: 15px;">Select All</th>
                                            <th>Department</th>
                                            <th>Date</th>
                                            <th>File Description</th>
                                            <th>HMM No.</th>
                                            <th>Subject</th>
                                            <th>Entry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($audits as $audit)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="audit_id" value="{{ $audit->audit_id }}" >
                                                <input type="checkbox" class="form-check-input" name="id[]" value="{{ $audit->id }}}" style="font-size: 15px;">
                                            </td>
                                            <td>{{ $audit->department?->name }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->audit?->date)->format('d-m-Y') }}</td>
                                            <td><span style="cursor: pointer" title="{{ $audit->audit?->description }}">{{ Str::limit($audit->audit?->description, '30') }}</span></td>
                                            <td>{{ $audit->objection_no }}</td>
                                            <td>{{ $audit->subject }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->entry_date)->format('d-m-Y') }}</td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" align="center"><h4>No Data Found</h4></td>
                                            </tr>
                                        @endforelse
                                </table>
                            </div>
                        </div>

                        @if(count($audits) > 0)
                        <div class="card-footer">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit" style="padding: 6px 8px 6px 10px">
                                <i data-feather="send"></i> Send
                            </button>
                        </div>
                        @endif

                    </div>
                </form>
            </div>
        </div>



@push('scripts')

    <script>
        $(document).ready(function(){
            $('body').on('click', '.viewObjection', function(){
                let id = $(this).attr('data-id');
                // alert(id)
            })
        })
    </script>


    {{-- Open modal and Add more --}}
    <script>
        var questionCounter = 1;

        // $("#buttons-datatables").on("click", ".add-objection", function(e) {
        //     e.preventDefault();
        //     var model_id = $(this).attr("data-id");
        //     $('#audit_id').val(model_id)
        //     var url = "{{ route('objection.getDymcaSendObjections') }}";

        //     $.ajax({
        //         url: url,
        //         type: 'GET',
        //         data: {
        //             'audit_id': model_id,
        //         },
        //         beforeSend: function()
        //         {
        //             $('#preloader').css('opacity', '0.5');
        //             $('#preloader').css('visibility', 'visible');
        //         },
        //         success: function(data, textStatus, jqXHR)
        //         {
        //             if (!data.error)
        //             {
        //                 var html = ``;
        //                 var count = 1;
        //                 $.each(data.auditObjections, function(index, value){
        //                     html += `<tr>
        //                         <td>
        //                         <input type="hidden" name="audit_id" value="${value.audit_id}" >
        //                         <input type="checkbox" class="form-checkbox" name="id[]" value="${value.id}" ></td>
        //                         <td>${count++}</td>
        //                         <td>${value?.department?.name}</td>
        //                         <td>${value.objection_no}</td>
        //                         <td>${value.subject}</td>
        //                         <td><a target="_blank" href="{{ route('objection.view-forward-objection-to-department') }}?id=${value.id}" class="btn btn-sm btn-primary viewObjection" data-id="${value.id}">View File</a></td>
        //                     </tr>`;
        //                 });
        //                 $('#modelObjectionId').html(html);

        //                 $('.viewObjectionDetails').addClass('d-none')

        //                 $("#addObjectionModal").modal("show");
        //             } else {
        //                 swal("Error!", data.error, "error");
        //             }
        //         },
        //         error: function(error, jqXHR, textStatus, errorThrown) {
        //             swal("Error!", "Some thing went wrong", "error");
        //         },
        //         complete: function() {
        //             $('#preloader').css('opacity', '0');
        //             $('#preloader').css('visibility', 'hidden');
        //         },
        //     });

        //     $('#assign-role-modal').modal('show');
        // });


        // Submit Objection Form
        $("#addForm").submit(function(e) {
            e.preventDefault();
            $("#addObjectionSubmit").prop('disabled', true);

            var formdata = new FormData(this);

            $.ajax({
                url: '{{ route('storeForwardObjectionToDepartment') }}',
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

