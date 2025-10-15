@extends('layouts.mantis')

@section('content')
<style>
    .user-badge {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.5px;
        padding: 8px 12px !important;
        border-radius: 8px !important;
    }

    .dept-badge {
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.3px;
        padding: 6px 10px !important;
        border-radius: 6px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-center">Data User</h2>
            <div>
                <button type="button" class="btn btn-info btn-sm"
                        data-bs-toggle="modal" data-bs-target="#syncModal">
                    <i class="fas fa-sync"></i> Sinkronisasi Data
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada data user.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabel">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Departemen</th>
                                <th>Tanggal Dibuat</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->actual_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role)
                                            <span class="badge border border-primary user-badge text-blue-800">{{ $user->role->role_name }}</span>
                                        @else
                                            <span class="badge bg-secondary user-badge">Belum ada role</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php $deptInfo = $user->departemen_info; @endphp
                                        <span class="badge bg-{{ $deptInfo['color'] }} dept-badge text-white">
                                            <i class="{{ $deptInfo['icon'] }} me-1"></i>{{ $deptInfo['bagian'] }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#roleModal{{ $user->id }}"
                                                    title="Ganti Role">
                                                <i class="fas fa-user-cog"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Modal Ganti Role --}}
                @foreach ($users as $user)
                    <div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="roleModalLabel">
                                        <i class="fas fa-user-cog"></i> Ganti Role User
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center mb-3">
                                        <h6 class="text-muted">Ubah role untuk:</h6>
                                        <strong class="fs-5">{{ $user->name }}</strong>
                                        <p class="text-muted mb-0">{{ $user->email }}</p>
                                        @if($user->role)
                                            <p class="text-info">Role saat ini: <strong>{{ $user->role->role_name }}</strong></p>
                                        @endif
                                    </div>

                                    <form action="{{ route('users.update-role') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <div class="mb-3">
                                            <label for="role_id_{{ $user->id }}" class="form-label">Pilih Role Baru</label>
                                            <select name="role_id" id="role_id_{{ $user->id }}" class="form-select" required>
                                                <option value="">-- Pilih Role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                            @if($user->role_id == $role->id) selected @endif>
                                                        {{ $role->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Modal Sinkronisasi Data --}}
                <div class="modal fade" id="syncModal" tabindex="-1" aria-labelledby="syncModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="syncModalLabel">
                                    <i class="fas fa-sync"></i> Sinkronisasi Data User
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="fas fa-sync fa-3x text-info mb-3"></i>
                                    <h6>Sinkronisasi Data</h6>
                                    <p class="text-muted">
                                        Fitur ini akan memastikan semua nama dan email di tabel user tersinkronisasi
                                        dengan data di profil peserta dan mentor yang terkait.
                                    </p>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perhatian:</strong> Proses ini akan mengupdate nama dan email di profil peserta/mentor
                                        sesuai dengan data terbaru di tabel user.
                                    </div>
                                </div>

                                <form action="{{ route('users.sync-all') }}" method="POST">
                                    @csrf
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-sync"></i> Mulai Sinkronisasi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
