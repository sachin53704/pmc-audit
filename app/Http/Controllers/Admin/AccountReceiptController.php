<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Models\Receipt;
use App\Models\SubReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountReceiptController extends Controller
{

    public function index()
    {
        $receipts = Receipt::query()
            ->withCount([
                'subreceipts as dy_auditor_approved_count' => fn($q) => $q->where('dy_auditor_status', 1),
                'subreceipts as dy_auditor_rejected_count' => fn($q) => $q->where('dy_auditor_status', 2),
                'subreceipts as dy_mca_approved_count' => fn($q) => $q->where('dy_mca_status', 1),
                'subreceipts as dy_mca_rejected_count' => fn($q) => $q->where('dy_mca_status', 2),
                'subreceipts as mca_approved_count' => fn($q) => $q->where('mca_status', 1),
                'subreceipts as mca_rejected_count' => fn($q) => $q->where('mca_status', 2),
                'subreceipts as mca_pending_count' => fn($q) => $q->where('mca_status', 0),
                'subreceipts as dy_mca_pending_count' => fn($q) => $q->where('dy_mca_status', 0),
                'subreceipts as dy_auditor_pending_count' => fn($q) => $q->where('dy_auditor_status', 0),
            ])
            ->latest()
            ->get();
        // return $receipts;
        return view('admin.account-receipt')->with(['receipts' => $receipts]);
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
            $receipt = Receipt::create(Arr::only($input, Receipt::getFillables()));

            for ($i = 0; $i < $request->subreceiptCount; $i++) {
                if ($request->{'amount_' . $i}) {
                    SubReceipt::create([
                        'receipt_id' => $receipt->id,
                        'receipt_detail' => $request->{'detail_' . $i},
                        'amount' => $request->{'amount_' . $i},
                        'file' => 'storage/file/' . $request->{'sub_receipt_' . $i}->store('', 'file'),
                    ]);
                }
            }
            DB::commit();
            return response()->json(['success' => 'Receipt uploaded successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'uploading', 'receipt');
        }
    }


    public function show(Receipt $receipt) {}


    public function receiptDetails(Receipt $receipt)
    {
        $receipt->load('subreceipts');

        $receiptHtml = '
                <div class="col-md-4 mt-3">
                    <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                    <input type="text" placeholder="enter description" class="form-control" readonly name="description" value="' . $receipt->description . '">
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
                    <div class="mt-4">
                        <div id="editImageSection">
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
                                        <input type="text" placeholder="Enter details" class="form-control" readonly name="detail_' . $key . '" value="' . $subreceipt->receipt_detail . '">
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="col-form-label" for="amount_' . $key . '">Amount <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly name="amount_' . $key . '" type="number" value="' . $subreceipt->amount . '" placeholder="Enter Amount">
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <div class="mt-4">
                                                <a href="' . asset($subreceipt->file) . '" target="_blank" class="btn btn-primary">View File</a>
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
                                        <input type="text" readonly class="form-control" value="' . $subreceipt->dy_auditor_remark . '">
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
                                        <input type="text" readonly class="form-control" value="' . $subreceipt->dy_mca_remark . '">
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
                                        <input type="text" readonly class="form-control" value="' . $subreceipt->mca_remark . '">
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


    public function edit(Receipt $receipt)
    {
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
                        <input type="text" placeholder="Enter description" class="form-control" ' . $isReadonly . ' name="detail_' . $key . '" value="' . $subreceipt->receipt_detail . '">
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
                <div class="row editReceiptSection custm-card mx-1 d-none"></div>';
        }


        $response = [
            'result' => 1,
            'receipt' => $receipt,
            'subreceiptHtml' => $subreceiptHtml,
            'fileHtml' => $fileHtml,
        ];

        return $response;
    }


    public function update(Request $request, Receipt $receipt)
    {
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
            $receipt->update(Arr::only($input, Receipt::getFillables()));
            $receipt->load('subreceipts');

            for ($key = 0; $key < $request->subreceiptCount; $key++) {
                if ($request->{'amount_' . $key}) {
                    $subreceipt = $receipt->subreceipts[$key] ?? '';

                    $dyauditorStatus = ($subreceipt && $subreceipt != "") ? $subreceipt['dy_auditor_status'] == 2 ? 0 : $subreceipt['dy_auditor_status'] : 0;
                    $dymcaStatus = ($subreceipt && $subreceipt != "") ? $subreceipt['dy_mca_status'] == 2 ? 0 : $subreceipt['dy_mca_status'] : 0;
                    $mcaStatus = ($subreceipt && $subreceipt != "") ? $subreceipt['mca_status'] == 2 ? 0 : $subreceipt['mca_status'] : 0;

                    SubReceipt::updateOrCreate(
                        ['id' => $subreceipt['id'] ?? ''],
                        [
                            'receipt_id' => $receipt->id,
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

            return response()->json(['success' => 'Receipt updated successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'receipt');
        }
    }


    public function destroy(Receipt $receipt)
    {
        try {
            $receipt->delete();

            return response()->json(['success' => 'Receipt deleted successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'deleting', 'receipt');
        }
    }


    public function pendingReceipts(Request $request)
    {
        $userRole = Auth::user()->roles[0]->name;
        $receipts = [];

        if ($userRole == 'DY Auditor') {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_auditor_status', 0))->latest()->get();
        } else if ($userRole == 'DY MCA') {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where(['dy_auditor_status' => 1, 'dy_mca_status' => 0]))->latest()->get();
        } else {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where(['dy_mca_status' => 1, 'mca_status' => 0]))->latest()->get();
        }

        return view('admin.pending-receipts')->with(['receipts' => $receipts]);
    }

    public function approvedReceipts(Request $request)
    {
        $userRole = Auth::user()->roles[0]->name;
        $receipts = [];

        if ($userRole == 'DY Auditor') {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_auditor_status', 1))->latest()->get();
        } else if ($userRole == 'DY MCA') {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_mca_status', 1))->latest()->get();
        } else {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('mca_status', 1))->latest()->get();
        }

        return view('admin.approved-receipts')->with(['receipts' => $receipts]);
    }

    public function rejectedReceipts(Request $request)
    {
        $userRole = Auth::user()->roles[0]->name;
        $receipts = [];

        if ($userRole == 'DY Auditor') {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_auditor_status', 2))->latest()->get();
        } else if ($userRole == 'DY MCA') {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('dy_mca_status', 2))->latest()->get();
        } else {
            $receipts = Receipt::withWhereHas('subreceipts', fn($q) => $q->where('mca_status', 2))->latest()->get();
        }

        return view('admin.rejected-receipts')->with(['receipts' => $receipts]);
    }


    public function receiptInfo(Request $request, Receipt $receipt)
    {
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

        $dyAuditor = (Auth::user()->hasRole('DY Auditor')) ? '' : 'disabled';
        $dyMca = (Auth::user()->hasRole('DY MCA')) ? '' : 'disabled';
        $mca = (Auth::user()->hasRole('MCA')) ? '' : 'disabled';

        $dyAuditorRequired = (Auth::user()->hasRole('DY Auditor')) ? 'required' : '';
        $dyMcaRequired = (Auth::user()->hasRole('DY MCA')) ? 'required' : '';
        $mcaRequired = (Auth::user()->hasRole('MCA')) ? 'required' : '';

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
                        <a href="' . asset($subreceipt->file) . '" class="btn btn-primary" target="_blank">View File</a>
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_' . $key . '">DY Auditor Action</label>
                        <select ' . $dyAuditorRequired . ' ' . $dyAuditor . ' name="' . ($roleName == "DY Auditor" ? $actionFieldName : "") . '" ' . ($roleName == "DY Auditor" ? $isEditable : "readonly") . ' class="form-select dyaditorAction">
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
                        <select ' . $dyMcaRequired . ' ' . $dyMca . ' name="' . ($roleName == "DY MCA" ? $actionFieldName : "") . '" class="form-select dymcaAction" ' . ($roleName == "DY MCA" ? $isEditable : "readonly") . '>
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
                        <select ' . $mcaRequired . ' ' . $mca . ' name="' . ($roleName == "MCA" ? $actionFieldName : "") . '" class="form-select mcaAction" ' . ($roleName == "MCA" ? $isEditable : "readonly") . '>
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


    public function approveReceipts(Request $request, Receipt $receipt)
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

            $receipt->status = 2;
            $receipt->save();

            for ($i = 0; $i < count($request->subreceipt_id); $i++) {
                if ($request->{'action_' . $i}) {
                    $actionParamName = 'action_' . $i;
                    $actionRemarkParamName = 'action_remark_' . $i;
                    SubReceipt::where(['id' => $request->subreceipt_id[$i]])
                        ->update([
                            'status' => $request->{$actionParamName} == 1 ? constant("App\Models\SubReceipt::$approveConst") : constant("App\Models\SubReceipt::$rejectConst"),
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
