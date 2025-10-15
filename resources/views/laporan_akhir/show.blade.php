@extends('layouts.mantis')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('laporan-akhir.index') }}">Laporan Akhir</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <!-- Header dengan tombol navigasi -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Detail Laporan Akhir</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('laporan-akhir.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                @unless(Auth::user()->isPeserta())
                    @if($laporanAkhir->status == 'terima')
                        <span class="btn btn-secondary disabled" title="Laporan sudah diterima, tidak dapat diedit">
                            <i class="fas fa-lock me-1"></i> Edit (Terkunci)
                        </span>
                    @else
                        <a href="{{ route('laporan-akhir.edit', $laporanAkhir->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    @endif
                @endunless
            </div>
        </div>
    </div>

    <!-- Informasi Peserta -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi Peserta</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Nama Peserta</div>
                        <div class="col-md-8">: {{ $laporanAkhir->peserta->nama_lengkap ?? '-' }}</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Nomor Identitas</div>
                        <div class="col-md-8">: {{ $laporanAkhir->peserta->nomor_identitas ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Bagian</div>
                        <div class="col-md-8">: {{ $laporanAkhir->peserta->bagian->nama_bagian ?? '-' }}</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Tahun Magang</div>
                        <div class="col-md-8">: {{ $laporanAkhir->peserta->tanggal_mulai_magang ? \Carbon\Carbon::parse($laporanAkhir->peserta->tanggal_mulai_magang)->format('Y') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Laporan Akhir -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi Laporan Akhir</h5>
        </div>
        <div class="card-body">
            <div class="row g-4 ">
                <!-- Kolom Kiri - Informasi Dasar -->
                <div class="col-md-6 ">
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Tanggal Dibuat</div>
                        <div class="col-md-8">: {{ $laporanAkhir->created_at->format('d F Y H:i') }}</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Judul Laporan</div>
                        <div class="col-md-8">: {{ $laporanAkhir->judul_laporan }}</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 fw-bold">Status</div>
                        <div class="col-md-8">
                            : <span class="badge bg-{{ $laporanAkhir->status === 'terima' ? 'success' : ($laporanAkhir->status === 'tolak' ? 'danger' : ($laporanAkhir->status === 'draft' ? 'secondary' : 'warning')) }}">
                                {{ ucfirst($laporanAkhir->status) }}
                            </span>
                        </div>
                    </div>
                    <!-- Deskripsi Laporan -->
                    <div class="row g-3">
                        <div class="col-10">
                            <label class="fw-bold d-block">Deskripsi Laporan:</label>
                            <div class="border rounded p-3 mt-2" style="background-color: #f8f9fa; white-space: pre-line; font-family: inherit;">
                                {!! nl2br(e($laporanAkhir->deskripsi_laporan)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan - File, Approve, dan Feedback -->
                <div class="col-md-6">
                    <!-- File Lampiran -->
                    <div class="mb-3">
                        <label class="fw-bold d-block">File Lampiran:</label>
                        <div class="mt-2">
                            @if($laporanAkhir->file_path)
                                <a href="{{ Storage::url($laporanAkhir->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat File
                                </a>
                            @else
                                <span class="badge bg-secondary">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    <!-- Ubah Status -->
                    <div class="mb-3">
                        <label class="fw-bold d-block">Ubah Status:</label>
                        <div class="mt-2">
                            @if(Auth::user()->isPeserta())
                                <span class="badge bg-{{ $laporanAkhir->status === 'terima' ? 'success' : ($laporanAkhir->status === 'tolak' ? 'danger' : ($laporanAkhir->status === 'draft' ? 'secondary' : 'warning')) }}">
                                    {{ ucfirst($laporanAkhir->status) }}
                                </span>
                            @else
                                <form action="{{ route('laporan-akhir.updateStatus', $laporanAkhir->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="draft" {{ $laporanAkhir->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="review" {{ $laporanAkhir->status == 'review' ? 'selected' : '' }}>Review</option>
                                        <option value="terima" {{ $laporanAkhir->status == 'terima' ? 'selected' : '' }}>Terima</option>
                                        <option value="tolak" {{ $laporanAkhir->status == 'tolak' ? 'selected' : '' }}>Tolak</option>
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan Mentor/Admin -->
                    @if(!Auth::user()->isPeserta())
                        <!-- Catatan yang sudah ada -->
                        @if($laporanAkhir->catatan_mentor)
                            <div class="mb-3">
                                <label class="fw-bold d-block">Catatan dari Mentor:</label>
                                <div class="alert alert-{{ $laporanAkhir->status === 'tolak' ? 'warning' : 'info' }} mt-2 mb-0">
                                    @if($laporanAkhir->status === 'tolak')
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                    @else
                                        <i class="fas fa-info-circle me-2"></i>
                                    @endif
                                    {{ $laporanAkhir->catatan_mentor }}
                                </div>
                            </div>
                        @endif

                        <!-- Form Edit Catatan -->
                        <div class="mb-3">
                            <label class="fw-bold d-block">
                                @if($laporanAkhir->catatan_mentor)
                                    Edit Catatan:
                                @else
                                    Beri Catatan:
                                @endif
                            </label>
                            <form action="{{ route('laporan-akhir.updateStatus', $laporanAkhir->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $laporanAkhir->status }}">
                                <div class="mb-2">
                                    <textarea name="catatan_mentor" class="form-control" rows="3" placeholder="Masukkan catatan untuk peserta...">{{ $laporanAkhir->catatan_mentor }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-{{ $laporanAkhir->status === 'tolak' ? 'warning' : 'info' }} btn-sm">
                                    @if($laporanAkhir->catatan_mentor)
                                        Update Catatan
                                    @else
                                        Simpan Catatan
                                    @endif
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Untuk Peserta - Tampilkan catatan read-only -->
                        @if($laporanAkhir->catatan_mentor)
                            <div class="mb-3">
                                <label class="fw-bold d-block">Catatan dari Mentor:</label>
                                <div class="alert alert-{{ $laporanAkhir->status === 'tolak' ? 'warning' : 'info' }} mt-2 mb-0">
                                    @if($laporanAkhir->status === 'tolak')
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                    @else
                                        <i class="fas fa-info-circle me-2"></i>
                                    @endif
                                    {{ $laporanAkhir->catatan_mentor }}
                                </div>
                                @if($laporanAkhir->status === 'tolak')
                                    <small class="text-muted mt-2 d-block">Silakan lakukan perbaikan sesuai catatan di atas.</small>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endsection
