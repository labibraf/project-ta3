@extends('layouts.mantis')
@section('content')
<div>
    <a href="{{ route('bagian.index') }}" class="btn btn-secondary mb-3">
        < Kembali </a>
</div>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Detail peserta Departemen {{ $bagian->nama_bagian }}</h2>
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
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Detail Mentor {{ $bagian->nama_bagian }}</h2>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama mentor</th>
                    <th>email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bagian->mentor as $mentor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mentor->nama_mentor }}</td>
                    <td>{{ $mentor->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
