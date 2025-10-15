@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="mb-3">
        <a href="{{ route('peserta.index') }}" class="btn btn-secondary">
            < Kembali
        </a>
    </div>

    <div class="row">
        {{-- Informasi Utama --}}
        <div class="col-xl-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Peserta Magang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Nama Lengkap</label>
                                <p class="mb-0">{{ $peserta->nama_lengkap }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Nomor Identitas</label>
                                <p class="mb-0">{{ $peserta->nomor_identitas }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Email</label>
                                <p class="mb-0">{{ $peserta->email }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">No. Telepon</label>
                                <p class="mb-0">{{ $peserta->no_telepon }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Jenis Kelamin</label>
                                <p class="mb-0">{{ $peserta->jenis_kelamin }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Bagian</label>
                                <p class="mb-0">{{ $peserta->bagian?->nama_bagian ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Asal Instansi</label>
                                <p class="mb-0">{{ $peserta->asal_instansi }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Jurusan</label>
                                <p class="mb-0">{{ $peserta->jurusan }}</p>
                            </div>
                        </div>
                        @if($peserta->mentor)
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Mentor Penanggung Jawab</label>
                                <p class="mb-0">{{ $peserta->mentor->nama_mentor ?? '-' }}</p>
                                @if($peserta->mentor->nomor_identitas)
                                <small class="text-muted">Nomor Identitas: {{ $peserta->mentor->nomor_identitas }}</small>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="text-muted">Alamat</label>
                                <p class="mb-0">{{ $peserta->alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Magang --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Magang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Tipe Magang</label>
                                <p class="mb-0">{{ $peserta->tipe_magang }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Jumlah SKS</label>
                                <p class="mb-0">{{ $peserta->sks }} SKS</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Minimum waktu tugas</label>
                                <p class="mb-0">{{ $peserta->target_waktu_tugas }} Jam</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Tanggal Mulai</label>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($peserta->tanggal_mulai_magang)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Tanggal Selesai</label>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($peserta->tanggal_selesai_magang)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="text-muted">Durasi</label>
                                <p class="mb-0">
                                    {{ \Carbon\Carbon::parse($peserta->tanggal_mulai_magang)->diffInDays($peserta->tanggal_selesai_magang) }} Hari
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted d-block">Waktu Tugas Tercapai</label>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 me-3">{{ $peserta->waktu_tugas_tercapai }} Jam</h4>
                                    @if($peserta->bisa_laporan_akhir)
                                        <span class="badge bg-success rounded-pill px-3 py-2">Siap Laporan Akhir</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill px-3 py-2">Proses</span>
                                    @endif
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 5px; background-color: #e9ecef;">
                                    <div class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: {{ $peserta->progress_percentage }}%; border-radius: 5px;"
                                        aria-valuenow="{{ $peserta->progress_percentage }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">0%</small>
                                    <small class="text-muted badge bg-gray-400 rounded-pill px-3 py-2 fw-bold"><b>{{ $peserta->progress_percentage }}%</b></small>
                                    <small class="text-muted">100%</small>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted fst-italic">
                                        <i class="ti ti-info-circle me-1"></i>
                                        100% = {{ $peserta->waktu_maksimum }} jam (maksimal waktu tugas)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted d-block">Status</label>
                                <div class="mt-2">
                                    @if($peserta->bisa_laporan_akhir)
                                        <div class="alert alert-success d-flex align-items-center p-2" role="alert">
                                            <i class="ti ti-circle-check me-2 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <strong>Memenuhi Syarat Laporan Akhir</strong>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning d-flex align-items-center p-2" role="alert">
                                            <i class="ti ti-alert-circle me-2 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <strong>Belum Memenuhi Syarat</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto & Aksi --}}
        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Foto Peserta</h5>
                </div>
                <div class="card-body text-center">
                    @if($peserta->foto)
                        <img src="{{ asset('storage/foto_peserta/'.$peserta->foto) }}"
                             alt="Foto {{ $peserta->nama_lengkap }}"
                             class="img-fluid rounded mb-3"
                             style="max-height: 300px;">
                    @else
                        <div class="mb-3">
                            <i class="ti ti-user text-muted" style="font-size: 5rem;"></i>
                        </div>
                        <p class="text-muted">Foto tidak tersedia</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('peserta.edit', $peserta->id) }}" class="btn btn-warning">
                            <i class="ti ti-edit"></i> Edit Peserta
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            <i class="ti ti-trash"></i> Hapus Peserta
                        </button>
                    </div>
                </div>
            </div>

            {{-- Statistik Singkat --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <h4 class="mb-1">{{ $peserta->total_tugas }}</h4>
                            <p class="text-muted mb-0">Total Tugas</p>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1">{{ $peserta->tugas_selesai }}</h4>
                            <p class="text-muted mb-0">Tugas Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data peserta <strong>{{ $peserta->nama_lengkap }}</strong>?</p>
                <p class="text-muted">Data akan terhapus secara permanen dan tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.progress-bar {
   transition: width 0.6s ease-in-out;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.querySelector('.progress-bar');
    const targetWidth = progressBar.style.width;
    progressBar.style.width = '0%';
    setTimeout(() => {
        progressBar.style.width = targetWidth;
    }, 300);
});
</script>
@endsection
