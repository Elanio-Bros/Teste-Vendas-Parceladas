<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class System extends Controller
{
    public function view_login(): View
    {
        return view('base/header');
    }

    public function login(Request $request)
    {
        $auth = $this->validate($request, ['email' => 'email|required', 'password' => 'string|required']);

        if (Auth::attempt(['email' => $auth['email'], 'password' => $auth['password']])) {
            return response()->json(['message' => 'admin login'], 200);
        }

        return response()->json(['erro' => 'admin', 'message' => 'not login admin'], 406);
    }

    public function logout(Request $request)
    {

        if (Auth::check()) {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json(['message' => 'admin logout']);
        } else {
            return response()->json(['erro' => 'admin', 'message' => 'Unauthorized'], 401);
        }
    }
}
