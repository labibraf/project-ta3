@extends('layouts.mantis')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Data User</h2>
        </div>
        <div class="card-body">
            <table class="table table-sm" id="tabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role?->role_name }}</td>   
                            <td>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#roleModal{{ $user->id }}">
                                Ganti Role
                            </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($users as $user)
        <div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="roleModalLabel">Ganti Role</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2 text-center text-secondary fs-5">Ubah role untuk {{ $user->name }}</p>
                        <form action="{{ route('users.update-role') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div>
                                <label for="role_id">Tentukan Role Akses</label>
                                <select name="role_id" id="role_id" class="form-select">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2 w-100">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
