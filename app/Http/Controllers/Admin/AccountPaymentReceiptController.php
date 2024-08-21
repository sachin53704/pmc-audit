<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Models\PaymentReceipt;
use App\Models\SubPaymentReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountPaymentReceiptController extends Controller
{

    public function index()
    {
        $receipts = PaymentReceipt::query()
            ->withCount([
                'subreceipts as dy_auditor_approved_count' => fn($q) => $q->where('dy_auditor_status', 1),
                'subreceipts as dy_auditor_rejected_count' => fn($q) => $q->where('dy_auditor_status', 2),
                'subreceipts as dy_mca_approved_count' => fn($q) => $q->where('dy_mca_status', 1),
                'subreceipts as dy_mca_rejected_count' => fn($q) => $q->where('dy_mca_status', 2),
                'subreceipts as mca_approved_count' => fn($q) => $q->where('mca_status', 1),
                'subreceipts as mca_rejected_count' => fn($q) => $q->where('mca_status', 2),
            ])
            ->latest()
            ->get();

        return view('admin.account-payment-receipt')->with(['receipts' => $receipts]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $fieldArray['description'] = 'required';
        $fieldArray['from_date'] = 'required';
        $fieldArray['to_date'] = 'required';
        $fieldArray['amount'] = 'required';
        $fieldArray['receipt_file'] = 'required';
        $messageArray['description.required'] = 'Receipt description is required';
        $messageArray['from_date.required'] = 'From date is required';
        $messageArray['to_date.required'] = 'To date is required';
        $messageArray['amount.required'] = 'Amount is required';
        $messageArray['receipt_file.required'] = 'Receipt is required';

        for ($i = 0; $i < $request->subreceiptCount; $i++) {
            if ($request->{'amount_' . $i}) {
                $fieldArray['detail_' . $i] = 'required';
                $fieldArray['amount_' . $i] = 'required';
                $fieldArray['sub_receipt_' . $i] = 'required';
                $messageArray['detail_' . $i . '.required'] = 'Please type details';
                $messageArray['amount_' . $i . '.required'] = 'Please type amount';
                $messageArray['sub_receipt_' . $i . '.required'] = 'Please upload sub receipt';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try {
            $input = $validator->validated();
            $input['user_id'] = Auth::id();
            $input['file'] = 'storage/file/' . $request->receipt_file->store('', 'file');

            DB::beginTransaction();
            $receipt = PaymentReceipt::create(Arr::only($input, PaymentReceipt::getFillables()));

            for ($i = 0; $i < $request->subreceiptCount; $i++) {
                if ($request->{'amount_' . $i}) {
                    SubPaymentReceipt::create([
                        'payment_receipt_id' => $receipt->id,
                        'receipt_detail' => $request->{'detail_' . $i},
                        'amount' => $request->{'amount_' . $i},
                        'file' => 'storage/file/' . $request->{'sub_receipt_' . $i}->store('', 'file'),
                    ]);
                }
            }
            DB::commit();
            return response()->json(['success' => 'Payment Receipt uploaded successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'uploading', 'payment receipt');
        }
    }


    public function show(PaymentReceipt $payment_receipt) {}


    public function receiptDetails(PaymentReceipt $payment_receipt)
    {
        $receipt = $payment_receipt;
        $receipt->load('subreceipts');

        $receiptHtml = '
                <div class="col-md-4 mt-3">
                    <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" readonly name="description" style="max-height: 100px; min-height:100px">' . $receipt->description . '</textarea>
                    <span class="text-danger is-invalid description_err"></span>
                </div>
                <div class="col-md-4 mt-3">
                    <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span></label>
                    <input class="form-control" readonly name="from_date" type="date" onclick="this.showPicker()" value="' . $receipt->from_date . '" placeholder="Select From Date">
                    <span class="text-danger is-invalid from_date_err"></span>
                </div>
                <div class="col-md-4 mt-3">
                    <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span></label>
                    <input class="form-control" readonly name="to_date" type="date" onclick="this.showPicker()" value="' . $receipt->to_date . '" placeholder="Select To Date">
                    <span class="text-danger is-invalid to_date_err"></span>
                </div>
                <div class="col-md-4 mt-3">
                    <label class="col-form-label" for="amount">Amount <span class="text-danger">*</span></label>
                    <input class="form-control" readonly name="amount" type="number" value="' . $receipt->amount . '" placeholder="Enter Amount">
                    <span class="text-danger is-invalid amount_err"></span>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="card">
                        <div class="card-body" id="editImageSection">
                            <a href="' . asset($receipt->file) . '" class="btn btn-primary" target="_blank">View File</a>
                        </div>
                    </div>
                </div>';

        $receiptHtml .= '<div class="col-md-12 mt-4" style="border: 1px solid #cfcfcf;border-radius: 8px;">
                            <div class="col-12 mt-3">
                                <div class="alert alert-primary">
                                    <strong>Sub-Receipts Detail</strong>
                                </div>
                            </div>
                            <div class="col-12">';

        foreach ($receipt->subreceipts as $key => $subreceipt) {
            $receiptHtml .= '
                                <div class="row custm-card mx-1">
                                    <div class="col-12 mt-2">
                                        <strong>Sub Receipt ' . ($key + 1) . '</strong>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="col-form-label" for="detail_' . $key . '">Detail <span class="text-danger">*</span></label>
                                        <textarea class="form-control" readonly name="detail_' . $key . '" style="max-height: 100px; min-height: 100px">' . $subreceipt->receipt_detail . '</textarea>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="col-form-label" for="amount_' . $key . '">Amount <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly name="amount_' . $key . '" type="number" value="' . $subreceipt->amount . '" placeholder="Enter Amount">
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <div class="card mb-0 mt-4">
                                            <div class="card-body">
                                                <a href="' . asset($subreceipt->file) . '" target="_blank">View File</a>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-2 mt-3">
                                        <label class="col-form-label" for="action_' . $key . '">DY Auditor Action</label>
                                        <select readonly class="form-control">
                                            <option value="">Action</option>
                                            <option value="1" ' . ($subreceipt->dy_auditor_status == 1 ? "selected" : "") . '>Approve</option>
                                            <option value="2" ' . ($subreceipt->dy_auditor_status == 2 ? "selected" : "") . '>Reject</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <label class="col-form-label">DY Auditor Remark</label>
                                        <textarea readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $subreceipt->dy_auditor_remark . '</textarea>
                                    </div>

                                    <div class="col-md-2 mt-3">
                                        <label class="col-form-label" >DY MCA Action</label>
                                        <select class="form-control" readonly>
                                            <option value="">Action</option>
                                            <option value="1" ' . ($subreceipt->dy_mca_status == 1 ? "selected" : "") . '>Approve</option>
                                            <option value="2" ' . ($subreceipt->dy_mca_status == 2 ? "selected" : "") . '>Reject</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <label class="col-form-label">DY MCA Remark</label>
                                        <textarea readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $subreceipt->dy_mca_remark . '</textarea>
                                    </div>

                                    <div class="col-md-2 mt-3">
                                        <label class="col-form-label" for="action_' . $key . '">MCA Action</label>
                                        <select class="form-control" readonly>
                                            <option value="">Action</option>
                                            <option value="1" ' . ($subreceipt->mca_status == 1 ? "selected" : "") . '>Approve</option>
                                            <option value="2" ' . ($subreceipt->mca_status == 2 ? "selected" : "") . '>Reject</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <label class="col-form-label" for="action_remark_' . $key . '">MCA Remark</label>
                                        <textarea readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $subreceipt->mca_remark . '</textarea>
                                    </div>
                                </div>';
        }
        $receiptHtml .= '
                            </div>
                        </div>';


        $response = [
            'result' => 1,
            'receipt' => $receipt,
            'receiptHtml' => $receiptHtml,
        ];

        return $response;
    }


    public function edit(PaymentReceipt $payment_receipt)
    {
        $receipt = $payment_receipt;
        $receipt->load('subreceipts');
        $fileHtml = '
            <a class="btn btn-primary btn-md px-2 mt-2" href="' . asset($receipt->file) . '" target="_blank" >View File</a>
        ';

        $subreceiptHtml = '';
        foreach ($receipt->subreceipts as $key => $subreceipt) {
            $isReadonly = $subreceipt->dy_auditor_status != 1 || $subreceipt->dy_mca_status == 2 || $subreceipt->mca_status == 2 ? '' : 'readonly';
            $subreceiptHtml .= '
                <div class="row editReceiptSection custm-card mx-1">
                    <div class="col-12 mt-2">
                        <strong>Sub Receipt ' . ($key + 1) . '</strong>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="col-form-label" for="detail_' . $key . '">Detail <span class="text-danger">*</span></label>
                        <textarea class="form-control" ' . $isReadonly . ' name="detail_' . $key . '" style="max-height: 100px; min-height: 100px">' . $subreceipt->receipt_detail . '</textarea>
                        <span class="text-danger is-invalid detail_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="col-form-label" for="amount_' . $key . '">Amount <span class="text-danger">*</span></label>
                        <input class="form-control" ' . $isReadonly . ' name="amount_' . $key . '" type="number" value="' . $subreceipt->amount . '" placeholder="Enter Amount">
                        <span class="text-danger is-invalid amount_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="col-form-label" for="sub_receipt_' . $key . '">Upload Sub-Receipt<span class="text-danger">*</span></label>
                        <input type="file" ' . $isReadonly . ' name="sub_receipt_' . $key . '" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="text-danger is-invalid sub_receipt_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-2">
                        <div class="col-form-label" style="visibility:hidden">file</div>
                        <a class="btn btn-primary" href="' . asset($subreceipt->file) . '" target="_blank">View File</a>
                    </div>
                </div>';
        }

        if (count($receipt->subreceipts) == 0) {
            $subreceiptHtml .= '
                <div class="row editReceiptSection custm-card d-none mx-1"></div>';
        }


        $response = [
            'result' => 1,
            'receipt' => $receipt,
            'subreceiptHtml' => $subreceiptHtml,
            'fileHtml' => $fileHtml,
        ];

        return $response;
    }


    public function update(Request $request, PaymentReceipt $payment_receipt)
    {
        \Log::info($request->all());
        $receipt = $payment_receipt;
        $fieldArray['description'] = 'required';
        $fieldArray['from_date'] = 'required';
        $fieldArray['to_date'] = 'required';
        $fieldArray['amount'] = 'required';
        $messageArray['description.required'] = 'Receipt description is required';
        $messageArray['from_date.required'] = 'From date is required';
        $messageArray['to_date.required'] = 'To date is required';
        $messageArray['amount.required'] = 'Amount is required';

        for ($i = 0; $i < $request->subreceiptCount; $i++) {
            if ($request->{'amount_' . $i}) {
                $fieldArray['detail_' . $i] = 'required';
                $fieldArray['amount_' . $i] = 'required';
                $messageArray['detail_' . $i . '.required'] = 'Please type details';
                $messageArray['amount_' . $i . '.required'] = 'Please type amount';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        try {
            $input = $validator->validated();
            $input['user_id'] = Auth::id();
            $input['file'] = $request->receipt_file ? 'storage/file/' . $request->receipt_file->store('', 'file') : $receipt->file;

            DB::beginTransaction();
            $receipt->update(Arr::only($input, PaymentReceipt::getFillables()));
            $receipt->load('subreceipts');

            for ($key = 0; $key < $request->subreceiptCount; $key++) {
                if ($request->{'amount_' . $key}) {
                    $subreceipt = $receipt->subreceipts[$key] ?? '';

                    $dyauditorStatus = ($subreceipt && $subreceipt != "") ? $subreceipt['dy_auditor_status'] == 2 ? 0 : $subreceipt['dy_auditor_status'] : 0;
                    $dymcaStatus = ($subreceipt && $subreceipt != "") ? $subreceipt['dy_mca_status'] == 2 ? 0 : $subreceipt['dy_mca_status'] : 0;
                    $mcaStatus = ($subreceipt && $subreceipt != "") ? $subreceipt['mca_status'] == 2 ? 0 : $subreceipt['mca_status'] : 0;
                    SubPaymentReceipt::updateOrCreate(
                        ['id' => $subreceipt['id'] ?? ''],
                        [
                            'payment_receipt_id' => $receipt->id,
                            'receipt_detail' => $request->{'detail_' . $key},
                            'amount' => $request->{'amount_' . $key},
                            'file' => $request->{'sub_receipt_' . $key} ? 'storage/file/' . $request->{'sub_receipt_' . $key}->store('', 'file') : $subreceipt['file'],
                            'dy_auditor_status' => $dyauditorStatus,
                            'dy_mca_status' => $dymcaStatus,
                            'mca_status' => $mcaStatus,
                        ]
                    );
                }
            }
            DB::commit();

            return response()->json(['success' => 'Payment receipt updated successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'payment receipt');
        }
    }


    public function destroy(PaymentReceipt $payment_receipt)
    {
        try {
            $payment_receipt->delete();

            return response()->json(['success' => 'Payment receipt deleted successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'deleting', 'payment receipt');
        }
    }


    public function pendingReceipts(Request $request)
    {
        $userRole = Auth::user()->roles[0]->name;
        $receipts = [];

        if ($userRole == 'DY Auditor') {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_auditor_status', 0))->latest()->get();
        } else if ($userRole == 'DY MCA') {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where(['dy_auditor_status' => 1, 'dy_mca_status' => 0]))->latest()->get();
        } else {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where(['dy_mca_status' => 1, 'mca_status' => 0]))->latest()->get();
        }

        return view('admin.pending-payment-receipts')->with(['receipts' => $receipts]);
    }

    public function approvedReceipts(Request $request)
    {
        $userRole = Auth::user()->roles[0]->name;
        $receipts = [];

        if ($userRole == 'DY Auditor') {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_auditor_status', 1))->latest()->get();
        } else if ($userRole == 'DY MCA') {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_mca_status', 1))->latest()->get();
        } else {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('mca_status', 1))->latest()->get();
        }

        return view('admin.approved-payment-receipts')->with(['receipts' => $receipts]);
    }

    public function rejectedReceipts(Request $request)
    {
        $userRole = Auth::user()->roles[0]->name;
        $receipts = [];

        if ($userRole == 'DY Auditor') {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_auditor_status', 2))->latest()->get();
        } else if ($userRole == 'DY MCA') {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_mca_status', 2))->latest()->get();
        } else {
            $receipts = PaymentReceipt::withWhereHas('subreceipts', fn($q) => $q->where('mca_status', 2))->latest()->get();
        }

        return view('admin.rejected-payment-receipts')->with(['receipts' => $receipts]);
    }


    public function receiptInfo(Request $request, PaymentReceipt $payment_receipt)
    {
        $receipt = $payment_receipt;
        $roleName = Auth::user()->roles[0]->name;
        if ($roleName == 'DY MCA') {
            $receipt->load(['subreceipts' => fn($q) => $q->where('dy_auditor_status', 1)]);
        } else if ($roleName == 'MCA') {
            $receipt->load(['subreceipts' => fn($q) => $q->where('dy_mca_status', 1)]);
        } else {
            $receipt->load('subreceipts');
        }
        $fileHtml = '
            <a class="btn btn-primary btn-md px-2 mt-2" href="' . asset($receipt->file) . '" target="_blank" >View File</a>
        ';

        $roleWiseColumn = str_replace(' ', '_', strtolower($roleName));


        $dyAuditor = (Auth::user()->hasRole('DY Auditor')) ? '=' : 'disabled';
        $dyMca = (Auth::user()->hasRole('DY MCA')) ? '=' : 'disabled';
        $mca = (Auth::user()->hasRole('MCA')) ? '=' : 'disabled';

        $subreceiptHtml = '';
        foreach ($receipt->subreceipts as $key => $subreceipt) {
            $isEditable = $subreceipt->{$roleWiseColumn . '_status'} != 0 ? "readonly" : "";
            $actionFieldName = 'action_' . $key;
            $remarkFieldName = 'action_remark_' . $key;

            $subreceiptHtml .= '
                <div class="row editReceiptSection custm-card mx-1">
                    <input type="hidden" name="subreceipt_id[]" value="' . $subreceipt->id . '">
                    <div class="col-12 mt-2">
                        <strong>Sub Receipt ' . ($key + 1) . '</strong>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="col-form-label" for="detail_' . $key . '">Detail <span class="text-danger">*</span></label>
                        <textarea class="form-control" readonly name="detail_' . $key . '" style="max-height: 100px; min-height: 100px">' . $subreceipt->receipt_detail . '</textarea>
                        <span class="text-danger is-invalid detail_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="col-form-label" for="amount_' . $key . '">Amount <span class="text-danger">*</span></label>
                        <input class="form-control" readonly name="amount_' . $key . '" type="number" value="' . $subreceipt->amount . '" placeholder="Enter Amount">
                        <span class="text-danger is-invalid amount_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="card mb-0 mt-4">
                            <div class="card-body">
                                <a href="' . asset($subreceipt->file) . '" target="_blank">View File</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_' . $key . '">DY Auditor Action</label>
                        <select ' . $dyAuditor . ' name="' . ($roleName == "DY Auditor" ? $actionFieldName : "") . '" ' . ($roleName == "DY Auditor" ? $isEditable : "readonly") . ' class="form-select">
                            <option value="">Action</option>
                            <option value="1" ' . ($subreceipt->dy_auditor_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($subreceipt->dy_auditor_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid ' . ($roleName == "DY Auditor" ? $actionFieldName . "_err" : "") . '"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_remark_' . $key . '">DY Auditor Remark</label>
                        <textarea name="' . ($roleName == "DY Auditor" ? $remarkFieldName : "") . '" ' . ($roleName == "DY Auditor" ? $isEditable : "readonly") . ' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $subreceipt->dy_auditor_remark . '</textarea>
                        <span class="text-danger is-invalid ' . ($roleName == "DY Auditor" ? $remarkFieldName . "_err" : "") . '"></span>
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_' . $key . '">DY MCA Action</label>
                        <select ' . $dyMca . ' name="' . ($roleName == "DY MCA" ? $actionFieldName : "") . '" class="form-select" ' . ($roleName == "DY MCA" ? $isEditable : "readonly") . '>
                            <option value="">Action</option>
                            <option value="1" ' . ($subreceipt->dy_mca_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($subreceipt->dy_mca_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid ' . ($roleName == "DY MCA" ? $actionFieldName . "_err" : "") . '"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_remark_' . $key . '">DY MCA Remark</label>
                        <textarea name="' . ($roleName == "DY MCA" ? $remarkFieldName : "") . '" ' . ($roleName == "DY MCA" ? $isEditable : "readonly") . ' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $subreceipt->dy_mca_remark . '</textarea>
                        <span class="text-danger is-invalid ' . ($roleName == "DY MCA" ? $remarkFieldName . "_err" : "") . '"></span>
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_' . $key . '">MCA Action</label>
                        <select ' . $mca . ' name="' . ($roleName == "MCA" ? $actionFieldName : "") . '" class="form-select" ' . ($roleName == "MCA" ? $isEditable : "readonly") . '>
                            <option value="">Action</option>
                            <option value="1" ' . ($subreceipt->mca_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($subreceipt->mca_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid ' . ($roleName == "MCA" ? $actionFieldName . "_err" : "") . '"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_remark_' . $key . '">MCA Remark</label>
                        <textarea name="' . ($roleName == "MCA" ? $remarkFieldName : "") . '" ' . ($roleName == "MCA" ? $isEditable : "readonly") . ' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $subreceipt->mca_remark . '</textarea>
                        <span class="text-danger is-invalid ' . ($roleName == "MCA" ? $remarkFieldName . "_err" : "") . '"></span>
                    </div>
                </div>';
        }


        $response = [
            'result' => 1,
            'subreceiptHtml' => $subreceiptHtml,
            'receipt' => $receipt,
            'fileHtml' => $fileHtml,
        ];

        return $response;
    }


    public function approveReceipts(Request $request, PaymentReceipt $payment_receipt)
    {
        $fieldArray['subreceipt_id'] = 'required';
        $messageArray['subreceipt_id.required'] = 'Receipt id no not found';

        for ($i = 0; $i < count($request->subreceipt_id); $i++) {
            if ($request->{'action_' . $i}) {
                $fieldArray['action_remark_' . $i] = 'required';
                $messageArray['action_remark_' . $i . '.required'] = 'Please type approve/reject remark';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try {
            $roleName = Auth::user()->roles[0]->name;
            $roleWiseColumn = str_replace(' ', '_', strtolower($roleName));
            $approveConst = 'STATUS_' . strtoupper($roleWiseColumn) . '_APPROVED';
            $rejectConst = 'STATUS_' . strtoupper($roleWiseColumn) . '_REJECTED';

            DB::beginTransaction();

            $payment_receipt->status = 2;
            $payment_receipt->save();

            for ($i = 0; $i < count($request->subreceipt_id); $i++) {
                if ($request->{'action_' . $i}) {
                    $actionParamName = 'action_' . $i;
                    $actionRemarkParamName = 'action_remark_' . $i;
                    SubPaymentReceipt::where(['id' => $request->subreceipt_id[$i]])
                        ->update([
                            'status' => $request->{$actionParamName} == 1 ? constant("App\Models\SubPaymentReceipt::$approveConst") : constant("App\Models\SubPaymentReceipt::$rejectConst"),
                            $roleWiseColumn . '_status' => $request->{$actionParamName},
                            $roleWiseColumn . '_remark' => $request->{$actionRemarkParamName},
                            'action_by_' . $roleWiseColumn => Auth::user()->id
                        ]);
                }
            }
            DB::commit();

            return response()->json(['success' => 'Action successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'taking', 'action');
        }
    }
}
