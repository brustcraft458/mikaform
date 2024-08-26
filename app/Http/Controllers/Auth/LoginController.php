<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    function webLogin() {
        return view('login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            session()->flash('action_message', 'login_fail');
            return redirect()->route('login');
        }

        // Regenerate session to prevent session fixation attacks
        session()->flush();
        session()->regenerate();

        session()->flash('action_message', 'login_success');
        return redirect()->route('form_template');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout successful']);
    }
}
