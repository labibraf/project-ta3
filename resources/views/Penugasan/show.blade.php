{{-- resources/views/penugasan/show.blade.php --}}
@extends('layouts.mantis')

@section('content')
<div class="">
    <!-- Header dengan tombol navigasi -->
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Detail Penugasan</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('penugasans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @unless(Auth::user()->isPeserta())
                    @if($penugasan->status_tugas === 'Selesai' && $penugasan->is_approved == 1)
                        <span class="btn btn-secondary disabled" title="Tugas sudah di-approve, tidak dapat diedit">
                            <i class="fas fa-lock"></i> Edit (Terkunci)
                        </span>
                    @else
                        <a href="{{ route('penugasans.edit', $penugasan->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                @endunless
            </div>
        </div>
    </div>

    <div class="card">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi Penugasan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri - Informasi Dasar -->
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Tanggal Dibuat</th>
                            <td>: {{ $penugasan->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Judul Tugas</th>
                            <td>: {{ $penugasan->judul_tugas }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: {{ $penugasan->kategori }}</td>
                        </tr>
                        <tr>
                            <th>Beban Waktu</th>
                            <td>: {{ $penugasan->beban_waktu ?? '-' }} jam</td>
                        </tr>
                        <tr>
                            <th>Deadline</th>
                            <td>: {{ $penugasan->deadline ? \Carbon\Carbon::parse($penugasan->deadline)->format('d F Y') : '-' }}</td>
                        </tr>
                        @if($penugasan->kategori === 'Individu')
                        <tr>
                            <th>Nama Peserta</th>
                            <td>: {{ $penugasan->peserta->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bagian</th>
                            <td>: {{ $penugasan->bagian->nama_bagian ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Mentor</th>
                            <td>: {{ $penugasan->mentor->user->name ?? '-' }}</td>
                        </tr>
                        @endif
                    </table>

                    <!-- Target Waktu Peserta (untuk Divisi) - Dipindahkan ke sini -->
                    @if($penugasan->kategori === 'Divisi')
                    <div class="mb-3">
                        <label class="fw-bold">Peserta yang Ditugaskan:</label>
                        @php
                            $pesertaList = $penugasan->pesertas();
                        @endphp
                        @if($pesertaList->count() > 0)
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Peserta</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pesertaList as $peserta)
                                            @php
                                                // Ambil progress tertinggi dari laporan harian peserta untuk penugasan ini
                                                $progressPeserta = \App\Models\LaporanHarian::where('penugasan_id', $penugasan->id)
                                                    ->where('peserta_id', $peserta->id)
                                                    ->max('progres_tugas') ?? 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $peserta->user->name ?? $peserta->nama_lengkap ?? '-' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 10px; border: 1px solid #dee2e6; border-radius: 4px;">
                                                            <div class="progress-bar bg-success"
                                                                role="progressbar"
                                                                style="width: {{ $progressPeserta }}%; border-radius: 3px;"
                                                                aria-valuenow="{{ $progressPeserta }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span class="small">{{ $progressPeserta }}%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mt-2 mb-0">
                                <i class="fas fa-info-circle"></i> Belum ada peserta yang ditugaskan di bagian ini
                            </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Kolom Kanan - File, Status, Progress, dan Feedback -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">File Tugas:</label>
                        <div class="mt-1">
                            @if($penugasan->file)
                                <a href="{{ asset('storage/' . $penugasan->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-download"></i> Download File
                                </a>
                            @else
                                <span class="badge bg-secondary">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Status Progress:</label>
                                <div class="mt-1">
                                    @if(isset($currentProgress) && $currentProgress == 100)
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif(isset($currentProgress) && $currentProgress > 0)
                                        <span class="badge bg-warning text-dark">Dikerjakan</span>
                                    @else
                                        <span class="badge bg-primary">Belum</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Approve Tugas:</label>
                                <div class="mt-1">
                                    @if(Auth::user()->isPeserta())
                                        @if($penugasan->is_approved === 1)
                                            <span class="badge bg-success">Iya</span>
                                        @else
                                            <span class="badge bg-danger">Belum</span>
                                        @endif
                                    @else
                                        <form action="{{ route('penugasan.updateApprove', $penugasan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="is_approved" class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="this.form.submit()">
                                                <option value="0" {{ $penugasan->is_approved == 0 ? 'selected' : '' }}>Belum</option>
                                                <option value="1" {{ $penugasan->is_approved == 1 ? 'selected' : '' }}>Iya</option>
                                            </select>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Tugas -->
                    @if(isset($currentProgress) && $currentProgress > 0)
                    <div class="mb-3">
                        <label class="fw-bold">Progress Tugas:</label>
                        <div class="mt-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="progress flex-grow-1 me-3" style="height: 20px; border: 2px solid #dee2e6; border-radius: 6px;">
                                    <div class="progress-bar bg-success"
                                        role="progressbar"
                                        style="width: {{ $currentProgress }}%; border-radius: 4px;"
                                        aria-valuenow="{{ $currentProgress }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $currentProgress }}%
                                    </div>
                                </div>
                                <span class="fw-bold">{{ $currentProgress }}%</span>
                            </div>
                            @if(isset($latestLaporan))
                                <div class="small text-muted">
                                    @if($penugasan->kategori === 'Divisi')
                                        Progress tertinggi dari: {{ $latestLaporan->peserta->user->name ?? $latestLaporan->peserta->nama_lengkap ?? 'Peserta' }}
                                        <br>Terakhir diupdate: {{ $latestLaporan->created_at->format('d M Y H:i') }}
                                    @else
                                        Terakhir diupdate: {{ $latestLaporan->created_at->format('d M Y H:i') }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Feedback dan Form untuk Mentor/Admin -->
                    @if(!Auth::user()->isPeserta())
                        @if($penugasan->is_approved == 0 && (isset($currentProgress) && $currentProgress > 0))
                            <!-- Catatan Perbaikan -->
                            @if($penugasan->catatan)
                            <div class="mb-3">
                                <label class="fw-bold">Catatan Perbaikan dari Mentor:</label>
                                <div class="alert alert-warning mt-2 mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ $penugasan->catatan }}
                                </div>
                            </div>
                            @endif
                            <!-- Form Edit Catatan -->
                            <div class="mb-3">
                                <label class="fw-bold">
                                    @if($penugasan->catatan)
                                        Edit Catatan Perbaikan:
                                    @else
                                        Beri Catatan Perbaikan:
                                    @endif
                                </label>
                                <form action="{{ route('penugasan.updateApprove', $penugasan->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_approved" value="0">
                                    <div class="mb-2">
                                        <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan catatan perbaikan untuk peserta...">{{ $penugasan->catatan }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        @if($penugasan->catatan)
                                            Update Catatan
                                        @else
                                            Simpan Catatan
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($penugasan->is_approved == 1)
                            <!-- Feedback yang sudah ada -->
                            @if($penugasan->feedback)
                            <div class="mb-3">
                                <label class="fw-bold">Feedback Mentor:</label>
                                <div class="alert alert-info mt-2 mb-2">
                                    {{ $penugasan->feedback }}
                                </div>
                            </div>
                            @endif
                            <!-- Form Edit Feedback -->
                            <div class="mb-3">
                                <label class="fw-bold">
                                    @if($penugasan->feedback)
                                        Edit Feedback:
                                    @else
                                        Beri Feedback:
                                    @endif
                                </label>
                                <form action="{{ route('penugasan.updateApprove', $penugasan->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_approved" value="1">
                                    <div class="mb-2">
                                        <textarea name="feedback" class="form-control" rows="3" placeholder="Masukkan feedback...">{{ $penugasan->feedback }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        @if($penugasan->feedback)
                                            Update Feedback
                                        @else
                                            Simpan Feedback
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <!-- Untuk Peserta - Tampilkan feedback/catatan read-only -->
                        @if($penugasan->is_approved == 0 && $penugasan->catatan && (isset($currentProgress) && $currentProgress > 0))
                        <div class="mb-3">
                            <label class="fw-bold">Catatan Perbaikan dari Mentor:</label>
                            <div class="alert alert-warning mt-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $penugasan->catatan }}
                            </div>
                            <small class="text-muted">Silakan lakukan perbaikan sesuai catatan di atas sebelum tugas dapat di-approve.</small>
                        </div>
                        @endif

                        @if($penugasan->is_approved == 1 && $penugasan->feedback)
                        <div class="mb-3">
                            <label class="fw-bold">Feedback Mentor:</label>
                            <div class="alert alert-info mt-2">
                                {{ $penugasan->feedback }}
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Deskripsi Tugas -->
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi Tugas:</label>
                        <div class="border rounded p-3 mt-2" style="background-color: #f8f9fa;">
                            {!! nl2br(e($penugasan->deskripsi_tugas)) !!}
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- Log Laporan Harian -->
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">LOG Laporan Harian Pengerjaan Tugas</h5>
            @auth
                @if(Auth::user()->isPeserta() && $penugasan->is_approved != 1)
                <div class="d-flex gap-2">
                    <a href="{{ route('laporan_harian.create', $penugasan->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Laporan Harian
                    </a>
                </div>
                @elseif(Auth::user()->isPeserta() && $penugasan->is_approved == 1)
                <div class="alert alert-success alert-sm d-inline-block mb-0 px-3 py-2">
                    <i class="fas fa-check-circle"></i>
                    <strong>Tugas telah di-approve.</strong> Tidak dapat menambah laporan lagi.
                </div>
                @endif
            @endauth
        </div>
        <div class="card-body">
            @if($laporanHarians->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Belum ada laporan harian untuk penugasan ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                @if($penugasan->kategori == 'Divisi')
                                <th>Nama Peserta</th>
                                @endif
                                <th>Deskripsi Kegiatan</th>
                                <th>Progress</th>
                                <th>File</th>
                                @unless(Auth::user()->isPeserta())
                                <th>Opsi</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporanHarians as $index => $laporan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $laporan->created_at->format('d M Y') }}</td>
                                    @if($penugasan->kategori == 'Divisi')
                                    <td>{{ $laporan->peserta->user->name ?? '-' }}</td>
                                    @endif
                                    <td>{{ Str::limit($laporan->deskripsi_kegiatan, 50) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 15px; border: 1px solid #dee2e6; border-radius: 4px;">
                                                <div class="progress-bar bg-success"
                                                    role="progressbar"
                                                    style="width: {{ $laporan->progres_tugas }}%; border-radius: 3px;"
                                                    aria-valuenow="{{ $laporan->progres_tugas }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="small">{{ $laporan->progres_tugas }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($laporan->file)
                                            <a href="{{ asset('storage/' . $laporan->file) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    @unless(Auth::user()->isPeserta())
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#hapusLaporanModal{{ $laporan->id }}"
                                                title="Hapus Laporan">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="hapusLaporanModal{{ $laporan->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Konfirmasi Hapus Laporan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus laporan harian tanggal <strong>{{ $laporan->created_at->format('d M Y') }}</strong>?</p>
                                                        <p class="text-muted">Data akan terhapus secara permanen.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('laporan-harian.destroy', $laporan->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @endunless
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
