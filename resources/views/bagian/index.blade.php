@extends('layouts.mantis')
@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Data Bagian</h2>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bagian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bagians as $bagian)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bagian->nama_bagian }}</td>
                        <td>
                            <a href="{{ route('bagian.show', $bagian->id) }}" class="btn btn-primary">detail</a>
                            <a href="{{ route('bagian.destroy', $bagian->id) }}" class="btn btn-danger" data-confirm-delete="true">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
