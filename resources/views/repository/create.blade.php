{{-- resources/views/repository/create.blade.php --}}
@extends('layouts.mantis')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('repository.index') }}">
                    <i class="fas fa-book-open me-1"></i>Repository
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Repository</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body bg-gradient-primary text-white p-4">
                    <h3 class="mb-2">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Repository Baru
                    </h3>
                    <p class="mb-0 opacity-75">Publikasikan laporan akhir yang sudah di-ACC ke repository</p>
                </div>
            </div>

            {{-- Info Alert --}}
            @if($laporanAkhirs->isEmpty())
            <div class="alert alert-warning" role="alert">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>Tidak Ada Laporan Akhir yang Tersedia
                </h5>
                <p class="mb-0">
                    Semua laporan akhir yang sudah di-ACC telah dipublikasikan ke repository,
                    atau belum ada laporan akhir yang di-ACC.
                </p>
                <hr>
                <a href="{{ route('laporan-akhir.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left me-1"></i>Lihat Laporan Akhir
                </a>
            </div>
            @else
            {{-- Form Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('repository.store') }}">
                        @csrf

                        {{-- Pilih Laporan Akhir --}}
                        <div class="mb-4">
                            <label for="laporan_akhir_id" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>Pilih Laporan Akhir <span class="text-danger">*</span>
                            </label>
                            <select name="laporan_akhir_id"
                                    id="laporan_akhir_id"
                                    class="form-select @error('laporan_akhir_id') is-invalid @enderror"
                                    required
                                    onchange="updateFormFromLaporan()">
                                <option value="">-- Pilih Laporan Akhir --</option>
                                @foreach($laporanAkhirs as $laporan)
                                    <option value="{{ $laporan->id }}"
                                            data-judul="{{ $laporan->judul_laporan }}"
                                            data-deskripsi="{{ $laporan->deskripsi_laporan }}"
                                            data-peserta="{{ $laporan->peserta->nama_lengkap ?? ($laporan->peserta->user->name ?? 'N/A') }}"
                                            data-bagian="{{ $laporan->peserta->bagian->nama_bagian ?? '' }}"
                                            {{ old('laporan_akhir_id', $selectedLaporanId) == $laporan->id ? 'selected' : '' }}>
                                        {{ $laporan->judul_laporan }} - {{ $laporan->peserta->nama_lengkap ?? ($laporan->peserta->user->name ?? 'N/A') }} ({{ $laporan->created_at->format('Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('laporan_akhir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Pilih laporan akhir yang sudah di-ACC untuk dipublikasikan ke repository
                            </small>
                        </div>

                        {{-- Preview Laporan (akan muncul setelah memilih) --}}
                        <div id="laporanPreview" class="alert alert-info border-start border-info border-4 mb-4" style="display: none;">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Preview Laporan</h6>
                            <div id="previewContent"></div>
                        </div>

                        <hr class="my-4">

                        {{-- Judul Repository --}}
                        <div class="mb-4">
                            <label for="judul" class="form-label">
                                <i class="fas fa-heading me-1"></i>Judul Repository
                            </label>
                            <input type="text"
                                   name="judul"
                                   id="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}"
                                   placeholder="Kosongkan jika ingin sama dengan judul laporan">
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Jika dikosongkan, akan menggunakan judul dari laporan akhir
                            </small>
                        </div>

                        {{-- Deskripsi Singkat --}}
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Deskripsi Singkat <span class="text-danger">*</span>
                            </label>
                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      rows="3"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      required
                                      placeholder="Masukkan deskripsi singkat yang akan ditampilkan di halaman utama">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi Lengkap --}}
                        <div class="mb-4">
                            <label for="deskripsi_lengkap" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>Deskripsi Lengkap (Opsional)
                            </label>
                            <textarea name="deskripsi_lengkap"
                                      id="deskripsi_lengkap"
                                      rows="6"
                                      class="form-control @error('deskripsi_lengkap') is-invalid @enderror"
                                      placeholder="Masukkan deskripsi detail tentang magang, pembelajaran, hasil, dll">{{ old('deskripsi_lengkap') }}</textarea>
                            @error('deskripsi_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Tahun Magang --}}
                            <div class="col-md-4 mb-4">
                                <label for="tahun_magang" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Tahun Magang <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="tahun_magang"
                                       id="tahun_magang"
                                       class="form-control @error('tahun_magang') is-invalid @enderror"
                                       value="{{ old('tahun_magang', date('Y')) }}"
                                       min="2020"
                                       max="{{ date('Y') + 1 }}"
                                       required>
                                @error('tahun_magang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bagian --}}
                            <div class="col-md-4 mb-4">
                                <label for="bagian" class="form-label">
                                    <i class="fas fa-building me-1"></i>Bagian/Divisi
                                </label>
                                <select name="bagian"
                                        id="bagian"
                                        class="form-select @error('bagian') is-invalid @enderror">
                                    <option value="">-- Pilih Bagian --</option>
                                    @foreach($bagians as $bagian)
                                        <option value="{{ $bagian->nama_bagian }}" {{ old('bagian') == $bagian->nama_bagian ? 'selected' : '' }}>
                                            {{ $bagian->nama_bagian }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bagian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="col-md-4 mb-4">
                                <label for="kategori" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Kategori
                                </label>
                                <select name="kategori"
                                        id="kategori"
                                        class="form-select @error('kategori') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('kategori') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status Publikasi --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_published"
                                       id="is_published"
                                       value="1"
                                       {{ old('is_published', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    <i class="fas fa-eye me-1"></i>Publikasikan langsung ke repository
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Jika tidak dicentang, repository akan disimpan sebagai draft
                            </small>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Repository
                            </button>
                            <a href="{{ route('repository.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>

<script>
    // Auto-fill form ketika laporan dipilih
    function updateFormFromLaporan() {
        const select = document.getElementById('laporan_akhir_id');
        const selectedOption = select.options[select.selectedIndex];
        const preview = document.getElementById('laporanPreview');
        const previewContent = document.getElementById('previewContent');

        if (selectedOption.value) {
            // Update form fields
            document.getElementById('deskripsi').value = selectedOption.dataset.deskripsi;
            document.getElementById('bagian').value = selectedOption.dataset.bagian;

            // Show preview
            previewContent.innerHTML = `
                <p class="mb-1"><strong>Judul:</strong> ${selectedOption.dataset.judul}</p>
                <p class="mb-1"><strong>Peserta:</strong> ${selectedOption.dataset.peserta}</p>
                <p class="mb-0"><strong>Bagian:</strong> ${selectedOption.dataset.bagian || '-'}</p>
            `;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Auto-trigger jika ada selected laporan
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('laporan_akhir_id');
        if (select.value) {
            updateFormFromLaporan();
        }
    });
</script>
@endsection
