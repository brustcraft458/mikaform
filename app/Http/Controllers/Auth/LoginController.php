<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    function webLogin()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            session()->flash('action_message', 'login_fail');
            return redirect()->route('login');
        }

        $input = $validator->validated();

        // Regenerate session to prevent session fixation attacks
        session()->flush();
        session()->regenerate();

        // Auth Login
        if (!Auth::attempt($input)) {
            session()->flash('action_message', 'login_fail_userpw');
            return redirect()->route('login');
        }

        session()->flash('action_message', 'login_success');
        return redirect()->route('form_template');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->flush();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}