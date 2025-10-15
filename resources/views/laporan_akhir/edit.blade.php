@extends('layouts.mantis')

@section('content')
<div class="card">
    <div>
        <a href="{{ route('laporan-akhir.index') }}" class="btn btn-secondary float-start mt-3 mr-2 text-center">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-header">
        <h2 class="text-left">Edit Laporan Akhir</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan-akhir.update', $laporanAkhir->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="judul_laporan">Judul Laporan</label>
                <input type="text" name="judul_laporan" id="judul_laporan"
                    class="form-control @error('judul_laporan') is-invalid @enderror"
                    value="{{ old('judul_laporan', $laporanAkhir->judul_laporan) }}" required autofocus autocomplete="off">
                @error('judul_laporan')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="deskripsi_laporan">Deskripsi Laporan</label>
                <textarea name="deskripsi_laporan" id="deskripsi_laporan" cols="30" rows="5"
                    class="form-control @error('deskripsi_laporan') is-invalid @enderror" required
                    autofocus autocomplete="off">{{ old('deskripsi_laporan', $laporanAkhir->deskripsi_laporan) }}</textarea>
                @error('deskripsi_laporan')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="file_path">Upload File (kosongkan jika tidak ingin ganti)</label>
                <input type="file" name="file_path" id="file_path"
                    class="form-control @error('file_path') is-invalid @enderror" autofocus>
                @error('file_path')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
                @if($laporanAkhir->file_path)
                    <div class="mt-2">
                        <a href="{{ Storage::url($laporanAkhir->file_path) }}" target="_blank" class="text-info">
                            <i class="fas fa-file-pdf"></i> File saat ini
                        </a>
                    </div>
                @endif
                <div class="form-text text-muted">
                    Format: PDF, DOC, DOCX. Maksimal 2MB.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui</button>
        </form>
    </div>
</div>
@endsection
