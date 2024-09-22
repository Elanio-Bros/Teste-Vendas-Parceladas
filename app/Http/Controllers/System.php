<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class System extends Controller
{
    public function view_login(): View
    {
        return view('login');
    }

    public function login(Request $request)
    {
        
    }

    public function logout()
    {
        return $this->view_login();
    }
}
