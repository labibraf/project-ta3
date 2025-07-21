@extends('layouts.mantis')

@section('content')
<div class="">
    <div>
        <a href="{{ route('peserta.index') }}" class="btn btn-secondary mb-3">
            < Kembali </a>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title">Tambah Peserta Magang</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('peserta.update', $peserta->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nama_lengkap">Nama Lengkap Peserta</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                        value="{{ $peserta->nama_lengkap }}"  autofocus>
                    @error('nama_lengkap')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="nomor_identitas">Nomor Identitas</label>
                    <input type="number" name="nomor_identitas" id="nomor_identitas"
                        class="form-control @error('nomor_identitas') is-invalid @enderror"
                        value="{{ $peserta->nomor_identitas }}"  readonly autofocus>
                    @error('nomor_identitas')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ $peserta->email }}"  autofocus>
                    @error('email')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="no_telepon">Nomor Telepon</label>
                    <input type="tel" name="no_telepon" id="no_telepon"
                        class="form-control @error('no_telepon') is-invalid @enderror"
                        value="{{ $peserta->no_telepon }}"  autofocus>
                    @error('no_telepon')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" cols="30" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        >{{ $peserta->alamat }}</textarea>
                    @error('alamat')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="form-control @error('jenis_kelamin') is-invalid @enderror" >
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki"
                            {{ $peserta->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan"
                            {{ $peserta->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="asal_instansi">Asal Instansi</label>
                    <input type="text" name="asal_instansi" id="asal_instansi"
                        class="form-control @error('asal_instansi') is-invalid @enderror"
                        value="{{ $peserta->asal_instansi }}"  autofocus>
                    @error('asal_instansi')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="jurusan">Jurusan</label>
                    <input type="text" name="jurusan" id="jurusan"
                        class="form-control @error('jurusan') is-invalid @enderror"
                        value="{{ $peserta->jurusan }}"  autofocus>
                    @error('jurusan')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="tipe_magang">Tipe Magang</label>
                    <select name="tipe_magang" id="tipe_magang"
                        class="form-control @error('tipe_magang') is-invalid @enderror" >
                        <option value="">-- Pilih Tipe Magang --</option>
                        <option value="Mandiri"
                            {{ $peserta->tipe_magang == 'Mandiri' ? 'selected' : '' }}>
                            Mandiri</option>
                        <option value="Pemerintah"
                            {{ $peserta->tipe_magang == 'Pemerintah' ? 'selected' : '' }}>
                            Pemerintah</option>
                        <option value="Undangan"
                            {{ $peserta->tipe_magang == 'Undangan' ? 'selected' : '' }}>
                            Undangan</option>
                    </select>
                    @error('tipe_magang')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="bagian_id">Bagian</label>
                    <select name="bagian_id" id="bagian_id"
                        class="form-control @error('bagian_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Bagian --</option>
                        @foreach ($bagians as $bagian)
                            <option value="{{ $bagian->id }}"
                                {{ $peserta->bagian_id == $bagian->id ? 'selected' : '' }}>
                                {{ $bagian->nama_bagian }}</option>
                        @endforeach
                    </select>
                    @error('bagian_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="tanggal_mulai_magang">Tanggal Mulai Magang</label>
                    <input type="date" name="tanggal_mulai_magang" id="tanggal_mulai_magang"
                        class="form-control @error('tanggal_mulai_magang') is-invalid @enderror"
                        value="{{ $peserta->tanggal_mulai_magang }}"  autofocus>
                    @error('tanggal_mulai_magang')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="tanggal_selesai_magang">Tanggal Selesai Magang</label>
                    <input type="date" name="tanggal_selesai_magang" id="tanggal_selesai_magang"
                        class="form-control @error('tanggal_selesai_magang') is-invalid @enderror"
                        value="{{ $peserta->tanggal_selesai_magang }}"  autofocus>
                    @error('tanggal_selesai_magang')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="foto">foto</label>
                    <input type="file" name="foto" id="foto"
                        class="form-control @error('foto') is-invalid @enderror"
                        value="{{ $peserta->foto }}"  autofocus>
                    @error('foto')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
                @if ($peserta->foto)
                    <div class="form-group mb-3">
                        <label>Foto Saat Ini:</label><br>
                        <img src="{{ asset('storage/foto_peserta/' . $peserta->foto) }}" alt="Foto Peserta" style="max-width: 100px; height: auto; ">
                    </div>
                @endif


                <div class="flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
