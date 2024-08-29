<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Display the user profile information.
     */
    public function show()
    {
        // Mendapatkan user yang sedang login
        $id_user = session('data_user_id');
        $user = User::find($id_user)->first();

        // Cek apakah pengguna sudah login
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your profile.');
        }
        // Melempar data user ke view
        return view('user-profile', ['user' => $user]);
    }
}