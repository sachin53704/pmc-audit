<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckSendEmailController extends Controller
{
    public function test(Request $request)
    {
        if ($request->email != "") {
            Mail::send('program-audit.mca.hmm.send-mail', ['body' => 'Sended HMM Draft to Department'], function ($message) {
                $message->from(config('details.from'), config('details.from'));
                $message->to(['sachin53704@gmail.com', 'coreoceantesting@gmail.com']);
                $message->subject('HMM Draft');

                $message->attach(storage_path('app/public/letter/04J2BxoYcsDNxdBW8SXLCrAkDtxD4FjwhGQq9grHDW6W9kVMWCqY4QbFut2T.pdf'), [
                    'as' => "04J2BxoYcsDNxdBW8SXLCrAkDtxD4FjwhGQq9grHDW6W9kVMWCqY4QbFut2T.pdf", // Rename the file if needed
                    'mime' => 'application/pdf', // Define the MIME type
                ]);
            });
            return "Email Send";
        } else {
            return "Something went Wrong";
        }
    }
}
