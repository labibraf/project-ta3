{{-- resources/views/laporan_harian/index.blade.php --}}
@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-center mb-0">Daftar Laporan Harian</h2>
            @if(Auth::user() && Auth::user()->isPeserta())
                @if(!Auth::user()->peserta->is_laporan_akhir_selesai)
                    <a href="{{ route('laporan_harian.create') }}" class="btn btn-primary">
                        (+) Tambah Laporan Harian
                    </a>
                @else
                    <span class="badge bg-success fs-6">Magang Selesai - Tidak Dapat Menambah Laporan</span>
                @endif
            @endif
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($laporanHarian->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada data laporan harian.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabel">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                @if(Auth::user() && Auth::user()->isAdmin())
                                <th>Nama Peserta</th>
                                <th>Bagian</th>
                                @endif
                                <th>Tanggal Laporan</th>
                                <th>Judul Tugas</th>
                                <th>Deskripsi Kegiatan</th>
                                <th>Progress</th>
                                <th>File</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporanHarian as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if(Auth::user()->isAdmin())
                                        <td>{{ $item->peserta->user->name ?? '-' }}</td>
                                        <td>{{ $item->peserta->bagian->nama_bagian ?? '-' }}</td>
                                    @endif
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>{{ $item->penugasan->judul_tugas ?? '-' }}</td>
                                    <td>{{ Str::limit($item->deskripsi_kegiatan, 50) }}</td>

                                    <!-- Perbaikan Progress Tugas -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                <div class="progress-bar bg-success"
                                                     role="progressbar"
                                                     style="width: {{ $item->progres_tugas }}%"></div>
                                            </div>
                                            <span class="small">{{ $item->progres_tugas }}%</span>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if($item->file)
                                            <a href="{{ asset('storage/' . $item->file) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('laporan_harian.edit', $item->id) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal{{ $item->id }}"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach($laporanHarian as $item)
<div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus laporan harian untuk tugas <strong>{{ $item->penugasan->judul_tugas ?? 'Tidak diketahui' }}</strong>?</p>
                <p class="text-muted">Data akan terhapus secara permanen.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('laporan_harian.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
