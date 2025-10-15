@extends('layouts.mantis')

@section('content')
<div class="">
    <div>
        <a href="{{ route('mentor.index') }}" class="btn btn-secondary mb-3">
            < Kembali </a>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title">Edit Mentor</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('mentor.update', $mentor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Informasi Pribadi --}}
                <div class="border-bottom mb-4">
                    <h4>Informasi Pribadi</h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_mentor">Nama Lengkap Mentor *</label>
                            <input type="text" name="nama_mentor" id="nama_mentor"
                                class="form-control @error('nama_mentor') is-invalid @enderror"
                                value="{{ old('nama_mentor', $mentor->nama_mentor) }}" required autofocus autocomplete="off">
                            @error('nama_mentor')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nomor_identitas">Nomor Identitas (NIK/KTP) *</label>
                            <input type="text" name="nomor_identitas" id="nomor_identitas"
                                class="form-control @error('nomor_identitas') is-invalid @enderror"
                                value="{{ old('nomor_identitas', $mentor->nomor_identitas) }}" required autocomplete="off">
                            @error('nomor_identitas')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $mentor->email) }}" required autocomplete="off">
                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_telepon">Nomor Telepon *</label>
                            <input type="tel" name="no_telepon" id="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                value="{{ old('no_telepon', $mentor->no_telepon) }}" required autocomplete="off">
                            @error('no_telepon')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jenis_kelamin">Jenis Kelamin *</label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $mentor->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $mentor->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bagian_id">Bagian *</label>
                            <select name="bagian_id" id="bagian_id"
                                class="form-control @error('bagian_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Bagian --</option>
                                @foreach ($bagians as $bagian)
                                    <option value="{{ $bagian->id }}" {{ old('bagian_id', $mentor->bagian_id) == $bagian->id ? 'selected' : '' }}>
                                        {{ $bagian->nama_bagian }}</option>
                                @endforeach
                            </select>
                            @error('bagian_id')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="alamat">Alamat *</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        required>{{ old('alamat', $mentor->alamat) }}</textarea>
                    @error('alamat')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- Informasi Profesional --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Informasi Profesional</h4>
                </div>

                <div class="form-group mb-3">
                    <label for="keahlian">Keahlian *</label>
                    <input type="text" name="keahlian" id="keahlian"
                        class="form-control @error('keahlian') is-invalid @enderror"
                        value="{{ old('keahlian', $mentor->keahlian) }}" required autocomplete="off"
                        placeholder="Contoh: Web Development, Database Management, UI/UX Design">
                    @error('keahlian')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Foto Mentor</h4>
                </div>

                <div class="form-group mb-3">
                    <label for="foto">Foto Mentor</label>
                    <input type="file" name="foto" id="foto"
                        class="form-control @error('foto') is-invalid @enderror">
                    <small class="form-text text-muted">
                        Format: JPG, PNG, GIF, SVG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.
                    </small>
                    @error('foto')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                @if ($mentor->foto)
                    <div class="form-group mb-3">
                        <label>Foto Saat Ini:</label><br>
                        <img src="{{ asset('storage/foto_mentor/' . $mentor->foto) }}"
                             alt="Foto {{ $mentor->nama_mentor }}"
                             class="img-thumbnail"
                             style="max-height: 150px;">
                    </div>
                @endif

                {{-- Tombol --}}
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    <a href="{{ route('mentor.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
