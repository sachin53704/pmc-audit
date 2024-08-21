<x-admin.layout>
    <x-slot name="title">Payment Receipt List</x-slot>
    <x-slot name="heading">Payment Receipt List</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <!-- Add Form -->
    <div class="row" id="addContainer" style="display:none;">
        <div class="col-sm-12">
            <div class="card">
                <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                    @csrf

                    <div class="card-header">
                        <h4 class="card-title">Add Payment Receipt</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" style="max-height: 100px; min-height:100px" required></textarea>
                                <span class="text-danger is-invalid description_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span></label>
                                <input class="form-control" name="from_date" type="date" onclick="this.showPicker()" placeholder="Select From Date" required>
                                <span class="text-danger is-invalid from_date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span></label>
                                <input class="form-control" name="to_date" type="date" onclick="this.showPicker()" placeholder="Select To Date" required>
                                <span class="text-danger is-invalid to_date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="amount">Amount <span class="text-danger">*</span></label>
                                <input class="form-control" name="amount" type="number" placeholder="Enter Amount" required>
                                <span class="text-danger is-invalid amount_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="receipt_file">Upload Receipt<span class="text-danger">*</span></label>
                                <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                <span class="text-danger is-invalid receipt_file_err"></span>
                            </div>

                            <div class="col-md-12 mt-4" style="border: 1px solid #cfcfcf;border-radius: 8px;">
                                <div class="col-12 mt-3">
                                    <div class="alert alert-primary">
                                        <strong>Add Sub-Receipts</strong>
                                    </div>
                                </div>

                                <div class="col-12" id="receiptSection">
                                    <div class="row receiptSection custm-card mx-1">
                                        <div class="col-12 mt-2">
                                            <strong>Sub Receipt 1</strong>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="detail_0">Detail <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="detail_0" style="max-height: 100px; min-height: 100px" required></textarea>
                                            <span class="text-danger is-invalid detail_0_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="amount_0">Amount <span class="text-danger">*</span></label>
                                            <input class="form-control" name="amount_0" type="number" placeholder="Enter Amount" required>
                                            <span class="text-danger is-invalid amount_0_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="sub_receipt_0">Upload Sub-Receipt<span class="text-danger">*</span></label>
                                            <input type="file" name="sub_receipt_0" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <span class="text-danger is-invalid sub_receipt_0_err"></span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end mb-3">
                                        <div class="col-md-6 col-sm-12">
                                            <button type="button" class="btn btn-danger remove float-start w-25 mt-2 mx-1">Remove</button>
                                            <button type="button" class="btn btn-primary add-more w-25 float-end mt-2 mx-1">Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- Edit Form --}}
    <div class="row" id="editContainer" style="display:none;">
        <div class="col">
            <form class="form-horizontal form-bordered" method="post" id="editForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Receipt</h4>
                    </div>
                    <div class="card-body py-2">
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="">
                        <div class="mb-3 row">

                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" style="max-height: 100px; min-height:100px"></textarea>
                                <span class="text-danger is-invalid description_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span></label>
                                <input class="form-control" name="from_date" type="date" onclick="this.showPicker()" placeholder="Select From Date">
                                <span class="text-danger is-invalid from_date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span></label>
                                <input class="form-control" name="to_date" type="date" onclick="this.showPicker()" placeholder="Select To Date">
                                <span class="text-danger is-invalid to_date_err"></span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="col-form-label" for="amount">Amount <span class="text-danger">*</span></label>
                                <input class="form-control" name="amount" type="number" placeholder="Enter Amount">
                                <span class="text-danger is-invalid amount_err"></span>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label class="col-form-label" for="receipt_file">Upload Receipt</label>
                                <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <span class="text-danger is-invalid receipt_file_err"></span>
                            </div>
                            <div class="col-md-3 mt-3">
                                    <div id="editImageSection" class="mt-4">
                                    </div>
                            </div>

                            <div class="col-md-12 mt-4" style="border: 1px solid #cfcfcf;border-radius: 8px;">
                                <div class="col-12 mt-3">
                                    <div class="alert alert-primary">
                                        <strong>Add Sub-Receipts</strong>
                                    </div>
                                </div>

                                <div class="col-12" id="editReceiptSection">
                                </div>
                                <div class="row justify-content-end mb-3">
                                    <div class="col-md-6 col-sm-12">
                                        <button type="button" class="btn btn-danger editFormRemove float-start w-25 mt-2 mx-1">Remove</button>
                                        <button type="button" class="btn btn-primary add-more-edit w-25 float-end mt-2 mx-1">Add More</button>
                                    </div>
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
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="">
                                <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Receipt Description</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>View Receipt</th>
                                    <th>View Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ Str::limit($receipt->description, 85) }}</td>
                                        <td>{{ $receipt->from_date }}</td>
                                        <td>{{ $receipt->to_date }}</td>
                                        <td>{{ $receipt->amount }}</td>
                                        <td>
                                            <strong class="text-success">DY Auditor Approved Count : {{ $receipt->dy_auditor_approved_count }}</strong> <br>
                                            <strong class="text-danger">DY Auditor Reject Count : {{ $receipt->dy_auditor_rejected_count }}</strong><br>
                                            <strong class="text-success">DY MCA Approved Count : {{ $receipt->dy_mca_approved_count }}</strong> <br>
                                            <strong class="text-danger">DY MCA Reject Count : {{ $receipt->dy_mca_rejected_count }}</strong><br>
                                            <strong class="text-success">MCA Approved Count : {{ $receipt->mca_approved_count }}</strong> <br>
                                            <strong class="text-danger">MCA Reject Count : {{ $receipt->mca_rejected_count }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ asset($receipt->file) }}" target="_blank" class="btn btn-primary btn-sm">View Receipt</a>
                                        </td>
                                        <td>
                                            <a data-id="{{ $receipt->id }}" class="btn view-detail btn-secondary btn-sm">View Sub Receipt</a>
                                        </td>
                                        <td>
                                            @if($receipt->mca_rejected_count > 0 || $receipt->dy_mca_rejected_count > 0 || $receipt->dy_auditor_rejected_count > 0 || $receipt->mca_pending_count > 0 || $receipt->dy_mca_pending_count > 0 || $receipt->dy_auditor_pending_count > 0)
                                                <button class="btn btn-secondary edit-element px-2 py-1" title="Edit receipt" data-id="{{ $receipt->id }}"><i data-feather="edit"></i></button>
                                            @endif
                                            @if ($receipt->status < 2)
                                                <button class="btn btn-danger rem-element px-2 py-1" title="Delete receipt" data-id="{{ $receipt->id }}"><i data-feather="trash-2"></i> </button>
                                            @endif
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



    <div class="modal fade" id="receiptInfoModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" >Receipt Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row" id="receiptDetailSect">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>



    @push('scripts')

        <!-- Get Detail -->
        <script>
            $("#buttons-datatables").on("click", ".view-detail", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                var url2 = "{{ route('payment-receipts.details', ':model_id') }}";

                $.ajax({
                    url: url2.replace(':model_id', model_id),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (!data.error)
                        {
                            $("#receiptDetailSect").html(data.receiptHtml)
                            $("#receiptInfoModal").modal("show");
                        }
                        else
                        {
                            swal('Error!', data.error, 'error');
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal('Error!', "Some thing went wrong", 'error');
                    },
                });
            });
        </script>


        {{-- Add more script --}}
        <script>
            var addFormCounter = 1;
            $(document).ready(function() {
                $(".add-more").click(function(e) {
                    e.preventDefault();

                    var newSection = `<div class="row receiptSection custm-card  mx-1">
                                        <div class="col-12 mt-2 mt-2">
                                            <strong>Sub Receipt ${addFormCounter+1}</strong>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="detail_${addFormCounter}">Detail <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="detail_${addFormCounter}" style="max-height: 100px; min-height: 100px"></textarea>
                                            <span class="text-danger is-invalid detail_${addFormCounter}_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="amount_${addFormCounter}">Amount <span class="text-danger">*</span></label>
                                            <input class="form-control" name="amount_${addFormCounter}" type="number" placeholder="Enter Amount">
                                            <span class="text-danger is-invalid amount_${addFormCounter}_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="sub_receipt_${addFormCounter}">Upload Sub-Receipt<span class="text-danger">*</span></label>
                                            <input type="file" name="sub_receipt_${addFormCounter}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                            <span class="text-danger is-invalid sub_receipt_${addFormCounter}_err"></span>
                                        </div>
                                    </div>`;

                    $(".receiptSection").last().after(newSection);
                    addFormCounter++;
                });

                $(document).on("click", ".remove", function() {
                    if ($(".receiptSection").length > 1) {
                        $(".receiptSection").last().remove();
                        addFormCounter--;
                    }
                });
            });


            $("#addForm").submit(function(e) {
                e.preventDefault();
                $("#addSubmit").prop('disabled', true);

                var formdata = new FormData(this);
                formdata.append('subreceiptCount', addFormCounter);
                $.ajax({
                    url: '{{ route('payment-receipts.store') }}',
                    type: 'POST',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#addSubmit").prop('disabled', false);
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
                            $("#addSubmit").prop('disabled', false);
                            resetErrors();
                            printErrMsg(responseObject.responseJSON.errors);
                        },
                        500: function(responseObject, textStatus, errorThrown) {
                            $("#addSubmit").prop('disabled', false);
                            swal("Error occured!", "Something went wrong please try again", "error");
                        }
                    }
                });
            });
        </script>


        <!-- Edit -->
        <script>
            var editFormCounter = 1;
            $("#buttons-datatables").on("click", ".edit-element", function(e) {
                e.preventDefault();
                var model_id = $(this).attr("data-id");
                var url = "{{ route('payment-receipts.edit', ':model_id') }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        editFormBehaviour();
                        if (!data.error) {
                            $("#editForm input[name='edit_model_id']").val(data.receipt.id);
                            $("#editForm textarea[name='description']").val(data.receipt.description);
                            $("#editForm input[name='from_date']").val(data.receipt.from_date);
                            $("#editForm input[name='to_date']").val(data.receipt.to_date);
                            $("#editForm input[name='amount']").val(data.receipt.amount);
                            $("#editForm #editReceiptSection").html(data.subreceiptHtml);
                            $("#editForm #editImageSection").html(data.fileHtml);
                            editFormCounter = data.receipt.subreceipts.length;
                        } else {
                            swal('Error!', data.error, 'error');
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal('Error!', "Some thing went wrong", 'error');
                    },
                });
            });

            $(document).ready(function() {
                $(".add-more-edit").click(function(e) {
                    e.preventDefault();

                    var newSection = `<div class="row editReceiptSection custm-card mx-1">
                                        <div class="col-12 mt-2 mt-2">
                                            <strong>Sub Receipt ${editFormCounter+1}</strong>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="detail_${editFormCounter}">Detail <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="detail_${editFormCounter}" style="max-height: 100px; min-height: 100px" required></textarea>
                                            <span class="text-danger is-invalid detail_${editFormCounter}_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="amount_${editFormCounter}">Amount <span class="text-danger">*</span></label>
                                            <input class="form-control" name="amount_${editFormCounter}" type="number" placeholder="Enter Amount" required>
                                            <span class="text-danger is-invalid amount_${editFormCounter}_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="col-form-label" for="sub_receipt_${editFormCounter}">Upload Sub-Receipt<span class="text-danger">*</span></label>
                                            <input type="file" name="sub_receipt_${editFormCounter}" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <span class="text-danger is-invalid sub_receipt_${editFormCounter}_err"></span>
                                        </div>
                                    </div>`;

                    $(".editReceiptSection").last().after(newSection);
                    editFormCounter++;
                });

                $(document).on("click", ".editFormRemove", function() {
                    if ($(".editReceiptSection").length > 1) {
                        $(".editReceiptSection").last().remove();
                        editFormCounter--;
                    }
                });
            });

            $(document).ready(function() {
                $("#editForm").submit(function(e) {
                    e.preventDefault();
                    $("#editSubmit").prop('disabled', true);
                    var formdata = new FormData(this);
                    formdata.append('_method', 'PUT');
                    formdata.append('subreceiptCount', editFormCounter);

                    var model_id = $('#edit_model_id').val();
                    var url = "{{ route('payment-receipts.update', ':model_id') }}";
                    //
                    $.ajax({
                        url: url.replace(':model_id', model_id),
                        type: 'POST',
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            $("#editSubmit").prop('disabled', false);
                            if (!data.error2)
                                swal("Successful!", data.success, "success")
                                .then((action) => {
                                    window.location.href = '{{ route('receipts.index') }}';
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



        <!-- Delete -->
        <script>
            $("#buttons-datatables").on("click", ".rem-element", function(e) {
                e.preventDefault();
                swal({
                        title: "Are you sure to delete this receipt file?",
                        // text: "Make sure if you have filled Vendor details before proceeding further",
                        icon: "info",
                        buttons: ["Cancel", "Confirm"]
                    })
                    .then((justTransfer) => {
                        if (justTransfer) {
                            var model_id = $(this).attr("data-id");
                            var url = "{{ route('payment-receipts.destroy', ':model_id') }}";

                            $.ajax({
                                url: url.replace(':model_id', model_id),
                                type: 'POST',
                                data: {
                                    '_method': "DELETE",
                                    '_token': "{{ csrf_token() }}"
                                },
                                success: function(data, textStatus, jqXHR) {
                                    if (!data.error && !data.error2) {
                                        swal("Success!", data.success, "success")
                                            .then((action) => {
                                                window.location.reload();
                                            });
                                    } else {
                                        if (data.error) {
                                            swal("Error!", data.error, "error");
                                        } else {
                                            swal("Error!", data.error2, "error");
                                        }
                                    }
                                },
                                error: function(error, jqXHR, textStatus, errorThrown) {
                                    swal("Error!", "Something went wrong", "error");
                                },
                            });
                        }
                    });
            });
        </script>
    @endpush

</x-admin.layout>
