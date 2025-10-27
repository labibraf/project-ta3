{{-- resources/views/repository/index.blade.php --}}
@extends('layouts.mantis')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 text-white">
                                <i class="fas fa-book-open me-2"></i>Repository Magang
                            </h2>
                            <p class="mb-0 mt-2 opacity-80">Koleksi Laporan Akhir Magang dari Peserta Terdahulu</p>
                        </div>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('repository.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Repository
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Section (Admin Only) --}}
    @if(Auth::user()->isAdmin())
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded p-3">
                                <i class="fas fa-database fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Repository</h6>
                            <h3 class="mb-0">{{ $statistics['total_repositories'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success text-white rounded p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Published</h6>
                            <h3 class="mb-0">{{ $statistics['total_published'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning text-white rounded p-3">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Draft</h6>
                            <h3 class="mb-0">{{ $statistics['total_draft'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info text-white rounded p-3">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Views</h6>
                            <h3 class="mb-0">{{ number_format($statistics['total_views']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Filter & Search Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('repository.index') }}" id="filterForm">
                        <div class="row g-3">
                            {{-- Search --}}
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Cari judul, deskripsi, kategori..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Filter Tahun --}}
                            <div class="col-md-2">
                                <select name="year" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Semua Tahun</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Kategori --}}
                            <div class="col-md-2">
                                <select name="category" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Bagian --}}
                            <div class="col-md-2">
                                <select name="bagian" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Semua Bagian</option>
                                    @foreach($bagians as $bagian)
                                        <option value="{{ $bagian->nama_bagian }}" {{ request('bagian') == $bagian->nama_bagian ? 'selected' : '' }}>
                                            {{ $bagian->nama_bagian }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Button Search & Reset --}}
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </button>
                                    <a href="{{ route('repository.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Repository List --}}
    <div class="row">
        @forelse($repositories as $repo)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm repository-card">
                <div class="card-body d-flex flex-column">
                    {{-- Badge Status & Views --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @if($repo->is_published)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Published
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Draft
                                </span>
                            @endif

                            @if($repo->kategori)
                                <span class="badge bg-primary">{{ $repo->kategori }}</span>
                            @endif
                        </div>
                        <span class="text-muted small">
                            <i class="fas fa-eye me-1"></i>{{ $repo->views }}
                        </span>
                    </div>

                    {{-- Judul --}}
                    <h5 class="card-title mb-3">
                        <a href="{{ route('repository.show', $repo->id) }}" class="text-decoration-none text-dark repo-title">
                            {{ Str::limit($repo->judul, 60) }}
                        </a>
                    </h5>

                    {{-- Deskripsi --}}
                    <p class="card-text text-muted small mb-3 flex-grow-1">
                        {{ Str::limit($repo->deskripsi, 120) }}
                    </p>

                    {{-- Meta Information --}}
                    <div class="border-top pt-3 mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">
                                <i class="fas fa-user me-1"></i>
                                {{ $repo->peserta->nama_lengkap ?? ($repo->peserta->user->name ?? 'N/A') }}
                            </span>
                            <span class="text-muted small">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $repo->tahun_magang }}
                            </span>
                        </div>

                        @if($repo->bagian)
                        <div class="text-muted small mb-2">
                            <i class="fas fa-building me-1"></i>
                            {{ $repo->bagian }}
                        </div>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('repository.show', $repo->id) }}" class="btn btn-sm btn-primary flex-fill">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>

                            @if(Auth::user()->isAdmin())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('repository.edit', $repo->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $repo->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada repository ditemukan</h5>
                    <p class="text-muted mb-0">
                        @if(request()->hasAny(['search', 'year', 'category', 'bagian']))
                            Coba ubah filter pencarian Anda.
                        @else
                            Belum ada repository yang tersedia saat ini.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
@if(Auth::user()->isAdmin())
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus repository ini?</p>
                <p class="text-danger small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .repository-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .repository-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .repo-title {
        transition: color 0.3s ease;
    }

    .repo-title:hover {
        color: #667eea !important;
    }

    .card-body {
        position: relative;
    }
</style>

<script>
    function confirmDelete(id) {
        const form = document.getElementById('deleteForm');
        form.action = `/repository/${id}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endsection
