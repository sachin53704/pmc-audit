<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ConfirmLoginType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->routeIs('confirm-login-type') || $request->routeIs('show-login-types'))
            return $next($request);

        $authUser = Auth::user();
        $userRole = $authUser->roles[0]->name;

        if( in_array($userRole, ['DY Auditor', 'DY MCA', 'MCA']) || $authUser->department_id == 1 )
        {
            if( !Session::get('LOGIN_TYPE') )
            {
                return response()->view('admin.auth.show-login-types');
            }
        }
        else
        {
            session()->put('LOGIN_TYPE', '1');
        }


        return $next($request);
    }
}
