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
        // Urutkan dari data terbaru ke lama dengan eager loading bagian
        $users = User::with(['role', 'peserta.bagian', 'mentor.bagian'])
                    ->orderBy('created_at', 'desc')
                    ->get();
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

        Alert::success('Success', 'Role berhasil diubah');
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = User::with(['role', 'peserta', 'mentor'])->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with(['peserta', 'mentor'])->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Sinkronisasi otomatis dengan tabel terkait
        $user->syncProfileData();

        Alert::success('Success', 'Data user berhasil diperbarui dan disinkronisasi dengan profil terkait');
        return redirect()->route('users.index');
    }

    /**
     * Sinkronisasi semua data user dengan profil terkait
     * Method ini bisa dipanggil untuk memperbaiki data yang tidak sinkron
     */
    public function syncAllUserData()
    {
        $users = User::with(['peserta', 'mentor'])->get();
        $synced = 0;

        foreach ($users as $user) {
            $user->syncProfileData();
            $synced++;
        }

        Alert::success('Success', "Berhasil menyinkronisasi {$synced} data user dengan profil terkait");
        return redirect()->route('users.index');
    }
}
