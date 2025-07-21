<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role_id' => 'required',
        ]);

        $userID = $request->user_id;
        $roleID = $request->role_id;

        $user = User::findOrFail($userID);
        $user->role_id = $roleID;
        $user->save();
        Alert::success('Role berhasil diubah');
        return redirect()->route('users.index');
    }
}
