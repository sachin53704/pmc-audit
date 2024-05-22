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
        $receipts = Receipt::get();

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
        $fieldArray['receipt'] = 'required';
        $messageArray['description.required'] = 'Receipt description is required';
        $messageArray['from_date.required'] = 'From date is required';
        $messageArray['to_date.required'] = 'To date is required';
        $messageArray['amount.required'] = 'Amount is required';
        $messageArray['receipt.required'] = 'Receipt is required';

        for($i=0; $i<$request->subreceiptCount; $i++)
        {
            if($request->{'amount_'.$i})
            {
                $fieldArray['detail_'.$i] = 'required';
                $fieldArray['amount_'.$i] = 'required';
                $fieldArray['sub_receipt_'.$i] = 'required';
                $messageArray['detail_'.$i.'.required'] = 'Please type details';
                $messageArray['amount_'.$i.'.required'] = 'Please type amount';
                $messageArray['sub_receipt_'.$i.'.required'] = 'Please upload sub receipt';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try
        {
            $input = $validator->validated();
            $input['user_id'] = Auth::id();
            $input['file'] = 'storage/file/'.$request->receipt->store('', 'file');

            DB::beginTransaction();
            $receipt = Receipt::create( Arr::only( $input, Receipt::getFillables()) );

            for($i=0; $i<$request->subreceiptCount; $i++)
            {
                if($request->{'amount_'.$i})
                {
                    SubReceipt::create([
                        'receipt_id' => $receipt->id,
                        'receipt_detail' => $request->{'detail_'.$i},
                        'amount' => $request->{'amount_'.$i},
                        'file' => 'storage/file/'.$request->{'sub_receipt_'.$i}->store('', 'file'),
                    ]);
                }
            }
            DB::commit();
            return response()->json(['success'=> 'Receipt uploaded successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'uploading', 'receipt');
        }
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Receipt $receipt)
    {
        $receipt->load('subreceipts');
        $fileHtml = '
            <a  class="btn btn-primary btn-md px-2 mt-2" href="'.asset($receipt->file).'" target="_blank" >View File</a>
        ';

        $subreceiptHtml = '';
        foreach($receipt->subreceipts as $key => $subreceipt)
        {
            $subreceiptHtml = '
                <div class="row editReceiptSection custm-card mx-1">
                    <div class="col-12 mt-2">
                        <strong>Sub Receipt '.($key+1).'</strong>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="col-form-label" for="detail_0">Detail <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="detail_0" style="max-height: 100px; min-height: 100px">'.$subreceipt->receipt_detail.'</textarea>
                        <span class="text-danger is-invalid detail_0_err"></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="col-form-label" for="amount_0">Amount <span class="text-danger">*</span></label>
                        <input class="form-control" name="amount_0" type="number" value="'.$subreceipt->amount.'" placeholder="Enter Amount">
                        <span class="text-danger is-invalid amount_0_err"></span>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="col-form-label" for="sub_receipt_0">Upload Sub-Receipt<span class="text-danger">*</span></label>
                        <input type="file" name="sub_receipt_0" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <span class="text-danger is-invalid sub_receipt_0_err"></span>
                    </div>
                    <div class="col-md-1 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <a href="'.asset($subreceipt->file).'" target="_blank">View File</a>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }


        $response = [
            'result' => 1,
            'receipt' => $receipt,
            'subreceiptHtml' => $subreceiptHtml,
            'fileHtml' => $fileHtml,
        ];

        return $response;
    }


    public function update(UpdateAuditRequest $request, Audit $audit)
    {
        try
        {
            $input = $request->validated();
            if($request->file)
            {
                $input['file_path'] = $request->file ? 'storage/file/'.$request->file->store('', 'file') : $audit->file_path;
                if(Storage::disk('file')->exists($audit->file_path))
                    Storage::disk('file')->delete($audit->file_path);
            }
            if( $audit->reject_reason != null )
            {
                $input['status'] = 1;
            }
            $audit->update( Arr::only( $input, Audit::getFillables() ) );

            return response()->json(['success'=> 'Audit file updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Audit file');
        }
    }


    public function destroy(Audit $audit)
    {
        try
        {
            $audit->delete();

            return response()->json(['success'=> 'Audit file deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Audit file');
        }
    }

}
