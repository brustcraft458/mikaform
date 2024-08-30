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

    public function handleManage(Request $request) {
        $id = $request->get('id');
        if ($request->has('user_change_role')) {
            return $this->ubahRole($request, $id);
        } elseif ($request->has('user_delete')) {
            return $this->hapusUser($id);
        }

        return response()->json(['none' => true]);
    }


    public function ubahRole($request, $id) {
        $request->validate([
            'role' => 'required|in:admin,member,super_admin',
        ]);

        $user = User::find($id);

        if (!$user) {
            session()->flash('action_message','user_change_role_fail');
            return redirect()->route('user_manage');
        }

        $user->role = $request->role;
        $user->save();

        session()->flash('action_message','user_change_role_success');
        return redirect()->route('user_manage');
    }

    public function hapusUser($id) {
        $user = User::findOrFail($id);
        $user->delete();

        if (!$user) {
            session()->flash('action_message','user_delete_fail');
            return redirect()->route('user_manage');
        }

        session()->flash('action_message','user_delete_success');
        return redirect()->route('user_manage');
    }

}