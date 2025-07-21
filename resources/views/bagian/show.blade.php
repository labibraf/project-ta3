@extends('layouts.mantis')
@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Detail Bagian {{ $bagian->nama_bagian }}</h2>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama peserta</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bagian->peserta as $peserta)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $peserta->nama_lengkap }}</td>
                    <td>{{ $peserta->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection