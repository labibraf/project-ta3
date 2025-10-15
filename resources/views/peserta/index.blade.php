{{-- resources/views/peserta/index.blade.php --}}

@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-center">Daftar Peserta Magang</h2>
            <a href="{{ route('peserta.create') }}" class="btn btn-primary">
                (+) Tambah Peserta Magang
            </a>
        </div>
        <div class="card-body">
            @if($peserta->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada data peserta magang.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabel">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Bagian</th>
                                <th>Waktu Magang</th>
                                <th>Foto</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Hapus @forelse dan @empty, gunakan @foreach saja --}}
                            @foreach($peserta as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $item->nama_lengkap }}
                                        @if($item->is_laporan_akhir_selesai)
                                            <br><span class="badge bg-success"><i class="fas fa-check"></i> Selesai</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->bagian?->nama_bagian ?? '-' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal_mulai_magang)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($item->tanggal_selesai_magang)->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">
                                            ({{ \Carbon\Carbon::parse($item->tanggal_mulai_magang)->diffInDays($item->tanggal_selesai_magang) }} hari)
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if($item->foto)
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalFoto{{ $item->id }}"> {{-- Gunakan id --}}
                                                <i class="fas fa-image"></i> Lihat
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('peserta.show', $item->id) }}" {{-- Gunakan peserta_id --}}
                                               class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('peserta.edit', $item->id) }}" {{-- Gunakan peserta_id --}}
                                               class="btn btn-warning btn-sm"
                                               title="{{ $item->is_laporan_akhir_selesai ? 'Edit (Data Akademis Terkunci)' : 'Edit' }}">
                                                <i class="fas fa-edit"></i>
                                                @if($item->is_laporan_akhir_selesai)
                                                    <i class="fas fa-lock"></i>
                                                @endif
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" {{-- Gunakan peserta_id --}}
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- Hapus @empty dan @endforelse --}}
                        </tbody>
                    </table>
                </div>

                {{-- Modal Konfirmasi Hapus --}}
                @foreach($peserta as $item)
                    <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> {{-- Gunakan id --}}
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Penghapusan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus data peserta <strong>{{ $item->nama_lengkap }}</strong>?</p>
                                    <p class="text-muted">Data akan terhapus secara permanen dan tidak dapat dikembalikan.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('peserta.destroy', $item->id) }}" method="POST"> {{-- Gunakan id --}}
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Modal Foto --}}
                @foreach ($peserta as $item)
                    @if($item->foto)
                        <div class="modal fade" id="modalFoto{{ $item->id }}" tabindex="-1" aria-labelledby="fotoModalLabel{{ $item->id }}" aria-hidden="true"> {{-- Gunakan id --}}
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fotoModalLabel{{ $item->id }}">Foto Peserta: {{ $item->nama_lengkap }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/foto_peserta/'.$item->foto) }}"
                                             alt="Foto {{ $item->nama_lengkap }}"
                                             class="img-fluid rounded">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            @endif {{-- Akhir dari @if($peserta->isEmpty()) --}}
        </div>
    </div>
</div>
@endsection
