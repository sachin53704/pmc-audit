<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;

class AuthController extends Controller
{

    public function showLogin()
    {
        $quotes = [];
        for ($i = 1; $i <= 3; $i++) {
            array_push($quotes, Inspiring::quote());
        }

        return view('admin.auth.login')->with(['quotes' => $quotes]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Please enter username',
                'password.required' => 'Please enter password',
            ]
        );

        if ($validator->passes()) {
            $username = $request->username;
            $password = $request->password;
            $remember_me = $request->has('remember_me') ? true : false;

            try {
                $user = User::where('email', $username)->first();

                if (!$user)
                    return response()->json(['error2' => 'No user found with this username']);

                if ($user->active_status == '0' && !$user->roles)
                    return response()->json(['error2' => 'You are not authorized to login, contact HOD']);

                if ($user->active_status == '0')
                    return response()->json(['error2' => 'You are not authorized to login, contact Admin']);

                $login_field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                if (!auth()->attempt([$login_field => $username, 'password' => $password], $remember_me))
                    return response()->json(['error2' => 'Your entered credentials are invalid']);

                $userType = $user->roles[0]->name;

                return response()->json(['success' => 'login successful', 'user_type' => $userType, 'user' => $user]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::info("login error:" . $e);
                return response()->json(['error2' => 'Something went wrong while validating your credentials!']);
            }
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }

    public function showLoginTypes(Request $request)
    {
        return view('admin.auth.show-login-types');
    }

    public function confirmLoginType(Request $request, $type)
    {
        session()->put('LOGIN_TYPE', $type == 1 ? $type : 2);

        return redirect()->route('dashboard');
    }


    public function showChangePassword()
    {
        return view('admin.auth.change-password');
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->passes()) {
            $old_password = $request->old_password;
            $password = $request->password;

            try {
                $user = DB::table('users')->where('id', $request->user()->id)->first();

                if (Hash::check($old_password, $user->password)) {
                    DB::table('users')->where('id', $request->user()->id)->update(['password' => Hash::make($password)]);

                    return response()->json(['success' => 'Password changed successfully!']);
                } else {
                    return response()->json(['error2' => 'Old password does not match']);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::info("password change error:" . $e);
                return response()->json(['error2' => 'Something went wrong while changing your password!']);
            }
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }
}
