@extends('layouts.mantis')

@section('content')
<div class="">
    <div>
        <a href="{{ route('penugasans.show', $laporanHarian->penugasan_id) }}" class="btn btn-secondary mb-3">
            < Kembali ke {{ $laporanHarian->penugasan->judul_tugas }}</a>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title">Edit Laporan Harian</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan_harian.update', $laporanHarian->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if(Auth::user() && Auth::user()->isAdmin())
                <div class="form-group mb-3">
                    <label for="peserta_id">Nama Peserta</label>
                    <input type="text" name="peserta_id" id="peserta_id"
                        class="form-control @error('peserta_id') is-invalid @enderror"
                        value="{{ $laporanHarian->peserta->user->name }}"  disabled>
                    @error('peserta_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="bagian_id">Bagian</label>
                    <input type="text" name="bagian_id" id="bagian_id"
                        class="form-control @error('bagian_id') is-invalid @enderror"
                        value="{{ $laporanHarian->peserta->bagian->nama_bagian }}"  disabled>
                    @error('bagian_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
                @endif
                <div class="form-group mb-3">
                    <label for="tanggal_laporan">Tanggal Laporan</label>
                    <input type="date" name="tanggal_laporan" id="tanggal_laporan"
                        class="form-control @error('tanggal_laporan') is-invalid @enderror"
                        value="{{ $laporanHarian->tanggal_laporan }}"  autofocus>
                    @error('tanggal_laporan')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="deskripsi_kegiatan">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" cols="30" rows="10"
                        class="form-control @error('deskripsi_kegiatan') is-invalid @enderror"
                        autofocus>{{ $laporanHarian->deskripsi_kegiatan }}</textarea>
                    @error('deskripsi_kegiatan')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="waktu_masuk">Waktu Masuk</label>
                    <input type="time" name="waktu_masuk" id="waktu_masuk"
                        class="form-control @error('waktu_masuk') is-invalid @enderror"
                        value="{{ $laporanHarian->waktu_masuk }}"  autofocus>
                    @error('waktu_masuk')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="waktu_pulang">Waktu Pulang</label>
                    <input type="time" name="waktu_pulang" id="waktu_pulang"
                        class="form-control @error('waktu_pulang') is-invalid @enderror"
                        value="{{ $laporanHarian->waktu_pulang }}"  autofocus>
                    @error('waktu_pulang')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="feedback_mentor">Feedback Mentor</label>
                    <textarea name="feedback_mentor" id="feedback_mentor" cols="30" rows="3"
                        class="form-control @error('feedback_mentor') is-invalid @enderror"
                        autofocus>{{ $laporanHarian->feedback_mentor }}</textarea>
                    @error('feedback_mentor')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
