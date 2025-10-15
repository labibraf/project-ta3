{{-- resources/views/laporan-akhir/index.blade.php --}}
@extends('layouts.mantis')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-center mb-0">Daftar Laporan Akhir</h2>

            @if(Auth::user()->isPeserta())
                @if(!Auth::user()->peserta->is_laporan_akhir_selesai && Auth::user()->peserta->bisa_laporan_akhir)
                    <a href="{{ route('laporan-akhir.create') }}" class="btn btn-primary">
                        (+) Buat Laporan Akhir
                    </a>
                @elseif(Auth::user()->peserta->is_laporan_akhir_selesai)
                    <span class="badge bg-success fs-6">Laporan Akhir Sudah Diterima - Magang Selesai</span>
                @else
                    <span class="badge bg-warning fs-6">Belum Memenuhi Syarat Laporan Akhir</span>
                @endif
            @endif

            @if(session('success'))
                <div class="alert alert-success mt-2 w-100">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="card-body">
            @if($laporanAkhir->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada data laporan akhir.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabel">
                        <thead class="table-dark">
                            @if(Auth::user()->isPeserta())
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Judul Laporan</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            @elseif(Auth::user()->isMentor())
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Nama Peserta</th>
                                    <th>Nomor Identitas</th>
                                    <th>Tahun Magang</th>
                                    <th>Keterangan</th>
                                    <th>Opsi</th>
                                </tr>
                            @else
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Nama Peserta</th>
                                    <th>Nomor Identitas</th>
                                    <th>Bagian</th>
                                    <th>Tahun Magang</th>
                                    <th>Keterangan</th>
                                    <th>Opsi</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach($laporanAkhir as $index => $item)
                                @if(Auth::user()->isPeserta())
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                        <td>{{ Str::limit($item->judul_laporan, 30) }}</td>
                                        <td>{{ Str::limit($item->deskripsi_laporan, 50) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status === 'terima' ? 'success' : ($item->status === 'tolak' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($item->status === 'draft')
                                                <a href="{{ route('laporan-akhir.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('laporan-akhir.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('laporan-akhir.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @elseif(Auth::user()->isMentor())
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                        <td>{{ $item->peserta->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $item->peserta->nomor_identitas ?? '-' }}</td>
                                        <td>{{ $item->peserta->tanggal_mulai_magang ? \Carbon\Carbon::parse($item->peserta->tanggal_mulai_magang)->format('Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status === 'terima' ? 'success' : ($item->status === 'tolak' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('laporan-akhir.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}"
                                                        title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                        <td>{{ $item->peserta->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $item->peserta->nomor_identitas ?? '-' }}</td>
                                        <td>{{ $item->peserta->bagian ? $item->peserta->bagian->nama_bagian : '-' }}</td>
                                        <td>
                                            {{ $item->peserta->tanggal_mulai_magang ? \Carbon\Carbon::parse($item->peserta->tanggal_mulai_magang)->format('Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $item->status === 'terima' ? 'success' : ($item->status === 'tolak' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('laporan-akhir.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus (hanya untuk admin) --}}
@foreach($laporanAkhir as $item)
    @if(!Auth::user()->isPeserta() && !Auth::user()->isMentor())
        <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus laporan akhir <strong>{{ Str::limit($item->judul_laporan, 30) }}</strong>?</p>
                        <p class="text-muted">Data akan terhapus secara permanen.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('laporan-akhir.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<style>
    .judul-tugas {
        color: #000000 !important;
        transition: color 0.3s ease;
    }

    .judul-tugas:hover {
        color: #0d6efd !important;
        text-decoration: underline !important;
    }
</style>
@endsection
