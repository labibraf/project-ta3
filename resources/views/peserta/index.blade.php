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
            <table class="table table-bordered" id="tabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>nomor_identitas</th>
                        <th>No Telepon</th>
                        <th>Asal Instansi</th>
                        <th>Jurusan</th>
                        <th>Tipe Magang</th>
                        <th>Bagian</th>
                        <th>Alamat</th>
                        <th>Tanggal Mulai Magang</th>
                        <th>Tanggal Selesai Magang</th>
                        <th>Foto</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peserta as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->nomor_identitas }}</td>
                            <td>{{ $item->no_telepon }}</td>
                            <td>{{ $item->asal_instansi }}</td>
                            <td>{{ $item->jurusan }}</td>
                            <td>{{ $item->tipe_magang }}</td>
                            <td>{{ $item->bagian?->nama_bagian }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>{{ $item->tanggal_mulai_magang }}</td>
                            <td>{{ $item->tanggal_selesai_magang }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalFoto{{ $item->id }}">
                                    Lihat Foto
                                </button>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('peserta.edit', $item->id) }}">Edit</a></li>
                                        <li><button type="button" class="btn text-danger" data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal{{ $item->id }}">
                                                Hapus
                                            </button></li>
                                        {{-- <li><a class="dropdown-item" href="#">Something else here</a></li> --}}
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@foreach($peserta as $item)
    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Lanjutkan Penghapusan Data ?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Data <b>{{ $item->nama_lengkap }}</b> akan terhapus secara permanen, klik <b>Lanjutkan</b> untuk menghapus data</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="{{ route('peserta.destroy', $item->id) }} "method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Lanjutkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($peserta as $item)
    <!-- Modal -->
<div class="modal fade" id="modalFoto{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Foto Pegawai</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('storage/foto_peserta/'.$item->foto) }}" alt="{{ $item->foto }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

