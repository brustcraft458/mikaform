<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function webLogin() {
        return view('login');
    }

    function webRegister() {
        return view('register');
    }

    function login(Request $request) {
        $auth = $request->only('username', 'password');

        if ($auth['username'] == 'test' && $auth['password'] == 'test') {
            session()->flash('action_message', 'login_success');
            return redirect()->route('form_template');
        } else {
            session()->flash('action_message', 'login_fail');
        }

        return redirect()->route('login');
    }
}
