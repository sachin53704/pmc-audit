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
                                                <button class="btn btn-info add-objection px-2 py-1" title="Add Objection" data-controls-modal="addObjectionModal" data-backdrop="static" data-keyboard="false" data-id="{{ $audit->id }}"> View Objection</button>
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
                        <h5 class="modal-title">View Objection </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sr no.</th>
                                            <th>Department</th>
                                            <th>HMM No.</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modelObjectionId">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" id="viewFooterObjectionDetails">
                        <button class="btn btn-secondary close-modal btn-sm" data-bs-dismiss="modal" type="button" >Cancel</button>
                        <button class="btn btn-primary btn-sm" id="saveObjectionStatus" type="submit"><i data-feather="send"></i> Forward To Department</button>
                    </div>
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

        $("#buttons-datatables").on("click", ".add-objection", function(e) {
            e.preventDefault();
            var model_id = $(this).attr("data-id");
            $('#audit_id').val(model_id)
            var url = "{{ route('objection.getDymcaSendObjections') }}";

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
                        var html = ``;
                        var count = 1;
                        $.each(data.auditObjections, function(index, value){
                            html += `<tr>
                                <td>
                                <input type="hidden" name="audit_id" value="${value.audit_id}" >
                                <input type="checkbox" class="form-checkbox" name="id[]" value="${value.id}" ></td>
                                <td>${count++}</td>
                                <td>${value?.department?.name}</td>
                                <td>${value.objection_no}</td>
                                <td>${value.subject}</td>
                                <td><a target="_blank" href="{{ route('objection.view-forward-objection-to-department') }}?id=${value.id}" class="btn btn-sm btn-primary viewObjection" data-id="${value.id}">View File</a></td>
                            </tr>`;
                        });
                        $('#modelObjectionId').html(html);

                        $('.viewObjectionDetails').addClass('d-none')

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

