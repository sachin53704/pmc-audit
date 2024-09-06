<x-admin.layout>
    <x-slot name="title">Pending Receipts</x-slot>
    <x-slot name="heading">Pending Receipts</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row" id="editContainer" style="display:none;">
        <div class="col">
            <form class="form-horizontal form-bordered" method="post" id="editForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Receipt Details</h4>
                    </div>
                    <div class="card-body py-2">
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                        <div class="mb-3 row">

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" readonly name="description" style="max-height: 100px; min-height:100px"></textarea>
                                <span class="text-danger is-invalid description_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="from_date" type="date" onclick="this.showPicker()" placeholder="Select From Date">
                                <span class="text-danger is-invalid from_date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="to_date" type="date" onclick="this.showPicker()" placeholder="Select To Date">
                                <span class="text-danger is-invalid to_date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="amount">Amount <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="amount" type="number" placeholder="Enter Amount">
                                <span class="text-danger is-invalid amount_err"></span>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div id="editImageSection" class="mt-4">
                                </div>
                            </div>

                            <div class="col-md-12 mt-4" style="border: 1px solid #cfcfcf;border-radius: 8px;">
                                <div class="col-12 mt-3">
                                    <div class="alert alert-primary">
                                        <div class="row">
                                            <div class="col-12 col-md-6 col-lg-6">
                                                <strong>Sub-Receipts</strong>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-6">
                                                <select class="form-select w-50 changeSubReceiptStatus">
                                                    <option value="">Select</option>
                                                    <option value="1">Approve</option>
                                                    <option value="2">Reject</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>

                                <div class="col-12" id="subreceiptSection">
                                </div>
                            </div>
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
                                    <th>Description</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Amount</th>
                                    <th>View File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $receipt->description }}</td>
                                        <td>{{ Carbon\Carbon::parse($receipt->from_date)->format('d-m-Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($receipt->to_date)->format('d-m-Y') }}</td>
                                        <td>{{ $receipt->amount }}</td>
                                        <td>
                                            <a href="{{ asset($receipt->file) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary edit-element px-2 py-1" title="View receipt" data-id="{{ $receipt->id }}"><i data-feather="file-text"></i> View Receipt</button>
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
                var url = "{{ route('receipts.info', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    beforeSend: function()
                    {
                        $('#preloader').css('opacity', '0.5');
                        $('#preloader').css('visibility', 'visible');
                    },
                    success: function(data, textStatus, jqXHR) {
                        editFormBehaviour();
                        if (!data.error)
                        {
                            $("#editForm input[name='edit_model_id']").val(data.receipt.id);
                            $("#editForm textarea[name='description']").val(data.receipt.description);
                            $("#editForm input[name='from_date']").val(data.receipt.from_date);
                            $("#editForm input[name='to_date']").val(data.receipt.to_date);
                            $("#editForm input[name='amount']").val(data.receipt.amount);
                            $("#editForm #editImageSection").html(data.fileHtml);
                            $("#editForm #subreceiptSection").html(data.subreceiptHtml);
                        }
                        else
                        {
                            alert(data.error);
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        alert("Some thing went wrong");
                    },
                    complete: function() {
                        $('#preloader').css('opacity', '0');
                        $('#preloader').css('visibility', 'hidden');
                    },
                });
            });
        </script>


        <!-- Approve Reject Answers -->
        <script>
            $(document).ready(function() {
                $("#editForm").submit(function(e) {
                    e.preventDefault();
                    $("#editSubmit").prop('disabled', true);
                    var formdata = new FormData(this);
                    formdata.append('_method', 'PUT');
                    var model_id = $('#edit_model_id').val();
                    var url = "{{ route('approve-receipts', ":model_id") }}";
                    //
                    $.ajax({
                        url: url.replace(':model_id', model_id),
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
                        },
                        complete: function() {
                            $('#preloader').css('opacity', '0');
                            $('#preloader').css('visibility', 'hidden');
                        },
                    });

                });
            });
        </script>


        <script>
            $(document).ready(function(){
                $('body').on('change', '.changeSubReceiptStatus', function(){
                    let status = $(this).val();
                    
                    @if(Auth::user()->hasRole("DY Auditor"))
                        let statusClass = ".dyaditorAction";
                    @elseif(Auth::user()->hasRole('DY MCA'))
                        let statusClass = ".dymcaAction";
                    @elseif(Auth::user()->hasRole('MCA'))
                        let statusClass = ".mcaAction";
                    @endif
                    if(status == "1"){
                        $('body').find(statusClass).val(1).change();
                    }else if(status == "2"){
                        $('body').find(statusClass).val(2).change();
                    }else{
                        $('body').find(statusClass).val("").change();
                    }
                })
            })
        </script>
    @endpush


</x-admin.layout>



