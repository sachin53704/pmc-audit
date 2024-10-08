<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizationController extends Controller
{
    public function index(Request $request, $lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);

        return redirect()->back();
    }
}
