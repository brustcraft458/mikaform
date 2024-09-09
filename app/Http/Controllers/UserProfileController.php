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
        // User
        $user = Auth::user();
        return view('user-profile', ['user' => $user]);
    }
}