<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Layout extends Component
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
        $themeMode = request()->cookie('theme-mode');
        if(!$themeMode)
            $themeMode = 'light';

        return view('components.admin.layout')->with(['themeMode' => $themeMode]);
    }
}
