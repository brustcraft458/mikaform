<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class KelolaUserController extends Controller
{
    //
    public function index() {
        $user_list = User::all();
        return view('kelola-user', ['user_list' => $user_list]);
    }


    public function ubahRole(Request $request, $id) {
        $request->validate([
            'role' => 'required|in:admin,member,super_admin',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('action_message', 'Role berhasil diubah');
    }

}