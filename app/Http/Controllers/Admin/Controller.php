<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{

    public function respondWith($data, $message = "Action successful.", $code = 200, $status = true ) {
        $response = [
            'success' => $status,
            'data'    => $data,
            'message' => $message ?? 'Action successful.'
        ];

        return response()->json($response, $code ?? 200);
    }

    public function respondWithAjax(\Exception $e, $action, $module)
    {
        Log::info("error while ".$action." ".$module." : ". $e);
        if (app()->environment('local'))
            return response()->json(['error2'=> $e->getMessage()]);
        return response()->json(['error2'=> "Something went wrong while ".$action." ".$module]);
    }
}
