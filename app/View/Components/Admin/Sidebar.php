<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $authUser = Auth::user();
        $userRole = $authUser->roles[0]->name;

        $canMenuVisible = true;
        if( (in_array($userRole, ['DY Auditor', 'DY MCA', 'MCA']) || $authUser->department_id == 1) )
        {
            if(!session('LOGIN_TYPE') || request()->routeIs('show-login-types'))
            {
                $canMenuVisible = false;
            }
        }


        return view('components.admin.sidebar')->with(['authUser' => $authUser, 'userRole' => $userRole, 'canMenuVisible' => $canMenuVisible]);
    }
}
