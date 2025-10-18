@extends('layouts.mantis')

@section('content')
<style>
    .card-grad-indigo { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card-grad-pink   { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .card-grad-blue   { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .card-grad-rose   { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
</style>

<div class="">
    <!-- Compact Filter Section -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('home') }}" class="row g-2 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1 small"><i class="ti ti-calendar"></i> Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small"><i class="ti ti-calendar-event"></i> Bulan</label>
                    <select name="bulan" class="form-select form-select-sm" id="bulanFilter">
                        <option value="">Semua</option>
                        <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Jan</option>
                        <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Feb</option>
                        <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Mar</option>
                        <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>Apr</option>
                        <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                        <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Jun</option>
                        <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Jul</option>
                        <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agu</option>
                        <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>Sep</option>
                        <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Okt</option>
                        <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>Nov</option>
                        <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Des</option>
                    </select>
                </div>
                <div class="col-md-5 col-sm-8">
                    <label class="form-label mb-1 small"><i class="ti ti-search"></i> Cari Peserta</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Nama, NIM, atau Instansi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 col-sm-4">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="ti ti-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Info Badge -->
    @if(request('tahun') || request('bulan') || request('search'))
        <div class="alert alert-info alert-dismissible fade show py-2 mb-3" role="alert">
            <small>
                <i class="ti ti-info-circle"></i> <strong>Filter:</strong>
                @if(request('tahun'))
                    <span class="badge bg-primary">Tahun: {{ request('tahun') }}</span>
                @endif
                @if(request('bulan'))
                    @php
                        $bulanNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        $bulanName = $bulanNames[request('bulan')] ?? request('bulan');
                    @endphp
                    <span class="badge bg-primary">Bulan: {{ $bulanName }}</span>
                @endif
                @if(request('search'))
                    <span class="badge bg-primary">Cari: "{{ request('search') }}"</span>
                @endif
                | Ditemukan: <strong>{{ $totalPeserta }}</strong> peserta
            </small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4 col-xl-4">
            <div class="card social-widget-card bg-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-users d-block f-46 text-white opacity-50"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-white mb-1">Total Peserta Aktif</p>
                            <h3 class="text-white mb-0">{{ number_format($pesertaAktif) }} / <span class="text-white opacity-75 small">{{ $totalPeserta }} Peserta</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-4">
            <div class="card social-widget-card bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-accessible d-block f-46 text-white opacity-50"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-white mb-1">Total Mentor Aktif</p>
                            <h3 class="text-white mb-0">{{ number_format($totalMentor) }} Mentor</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-4">
            <div class="card social-widget-card bg-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-building d-block f-46 text-white opacity-50"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-white mb-1">Total Departemen</p>
                            <h3 class="text-white mb-0">{{ number_format($totalBagian) }} Divisi</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-2 f-w-400 text-muted">Total Jam Magang</h6>
                            <h4 class="mb-3">{{ number_format($totalJamMagang) }}</h4>
                            <div class="small text-warning">
                                <i class="ti ti-clock"></i> Jam Tercapai
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-xl bg-light-warning">
                                <i class="ti ti-clock fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Status Overview -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-md-12">
            <div class="card h-100 mb-3 d-flex flex-column">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tren Pendaftaran Magang</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            6 Bulan Terakhir
                        </button>
                    </div>
                </div>
                <div class="card-body flex-grow-1">
                    <div id="monthly-trend-chart" class="h-100"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card h-100 mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Status Peserta Magang</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="py-3 rounded bg-light-primary">
                                <h3 class="mb-1 text-primary">{{ $pesertaAktif }}</h3>
                                <p class="mb-0">Aktif Magang</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="py-3 rounded bg-light-warning">
                                <h4 class="mb-1 text-warning">{{ $pesertaHampirSelesai }}</h4>
                                <p class="mb-0 small">Dibawah 50%</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="py-3 rounded bg-light-success">
                                <h4 class="mb-1 text-success">{{ $pesertaSelesai }}</h4>
                                <p class="mb-0 small">Diatas 50%</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="status-donut-chart" style="height: 200px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Peserta & Quick Actions -->
    <div class="row">
        <div class="col-xl-8 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-check-circle text-success me-2"></i>Peserta Selesai Magang
                    </h5>
                    <a href="{{ route('peserta.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Asal Instansi</th>
                                    <th>Bagian</th>
                                    <th>Progress</th>
                                    <th>Tanggal Selesai</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPeserta as $peserta)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($peserta->foto)
                                            <img src="{{ asset('storage/foto_peserta/'.$peserta->foto) }}"
                                                 class="rounded-circle me-2" width="40" height="40" alt="">
                                            @else
                                            <div class="avtar avtar-s bg-light-secondary me-2">
                                                <span>{{ substr($peserta->nama_lengkap, 0, 1) }}</span>
                                            </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $peserta->nama_lengkap }}</h6>
                                                <small class="text-muted">{{ $peserta->email ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $peserta->asal_instansi ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-light-primary">
                                            {{ $peserta->bagian?->nama_bagian ?? 'Belum Ditentukan' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success"
                                                         role="progressbar"
                                                         style="width: {{ $peserta->progress_percentage }}%"
                                                         aria-valuenow="{{ $peserta->progress_percentage }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="text-success fw-semibold">{{ $peserta->progress_percentage }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="ti ti-calendar-check me-1"></i>
                                            {{ $peserta->tanggal_selesai }}
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('peserta.show', $peserta->id) }}"
                                           class="btn btn-sm btn-icon btn-light"
                                           data-bs-toggle="tooltip"
                                           title="Detail Peserta">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-users-off fs-3 d-block mb-2"></i>
                                            Belum ada peserta yang menyelesaikan magang
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <!-- Quick Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('peserta.create') }}" class="btn btn-primary">
                            <i class="ti ti-user-plus me-2"></i> Tambah Peserta
                        </a>
                        <a href="{{ route('mentor.create') ?? '#' }}" class="btn btn-info">
                            <i class="ti ti-user-shield me-2"></i> Tambah Mentor
                        </a>
                        @if(Route::has('bagian.create'))
                        <a href="{{ route('bagian.create') }}" class="btn btn-success">
                            <i class="ti ti-building me-2"></i> Tambah Bagian
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Completions -->
            {{-- <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Akan Selesai Magang</h5>
                </div>
                <div class="card-body">
                    @forelse($upcomingCompletions as $peserta)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($peserta->foto)
                            <img src="{{ asset('storage/foto_peserta/'.$peserta->foto) }}"
                                 class="rounded-circle" width="35" height="35" alt="">
                            @else
                            <div class="avtar avtar-s bg-light-warning">
                                <span>{{ substr($peserta->nama_lengkap, 0, 1) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $peserta->nama_lengkap }}</h6>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($peserta->tanggal_selesai_magang)->format('d M Y') }}
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $peserta->bisa_laporan_akhir ? 'success' : 'warning' }} rounded-pill">
                                {{ $peserta->bisa_laporan_akhir ? 'Siap' : 'Proses' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <div class="text-muted">
                            <i class="ti ti-calendar-time fs-3 d-block mb-2"></i>
                            Tidak ada yang akan selesai dalam 30 hari
                        </div>
                    </div>
                    @endforelse
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Department Distribution -->
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Distribusi per Departemen</h5>
                    @if(Route::has('bagian.index'))
                    <a href="{{ route('bagian.index') }}" class="btn btn-sm btn-light">
                        <i class="ti ti-external-link"></i>
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div id="department-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Bagian</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Bagian</th>
                                    <th class="text-center">Peserta</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bagianDistribution as $bagian)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avtar avtar-s bg-light-primary me-2">
                                                <i class="ti ti-building"></i>
                                            </div>
                                            <span>{{ $bagian->nama_bagian }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-primary rounded-pill">
                                            {{ $bagian->peserta_count }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-muted">
                                            {{ $totalPeserta > 0 ? round(($bagian->peserta_count / $totalPeserta) * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-building fs-3 d-block mb-2"></i>
                                            Belum ada bagian terdaftar
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Pie Charts Section -->
    <div class="row">
        <!-- Laporan Akhir Status -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Laporan Akhir</h5>
                </div>
                <div class="card-body">
                    <div id="laporan-akhir-chart" style="height: 250px;"></div>
                    <div class="mt-3 text-center">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-1 text-success">{{ $laporanAkhirSelesai }}</h6>
                                <small class="text-muted">Selesai</small>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 text-warning">{{ $laporanAkhirBelum }}</h6>
                                <small class="text-muted">Belum</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Completion Status -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Penugasan</h5>
                </div>
                <div class="card-body">
                    <div id="task-status-chart" style="height: 250px;"></div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Selesai</small>
                            <small class="text-success font-weight-bold">{{ $tugasSelesai }}</small>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Berjalan</small>
                            <small class="text-warning font-weight-bold">{{ $tugasBerjalan }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Belum Dimulai</small>
                            <small class="text-danger font-weight-bold">{{ $tugasBelumDimulai }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Approval Status -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Persetujuan Tugas</h5>
                </div>
                <div class="card-body">
                    <div id="task-approval-chart" style="height: 280px;"></div>
                    <div class="mt-3 text-center">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-1 text-success">{{ $tugasApproved }}</h6>
                                <small class="text-muted">Disetujui</small>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 text-warning">{{ $tugasPendingApproval }}</h6>
                                <small class="text-muted">Menunggu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Target Achievement -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Batas Minimum Pencapaian</h5>
                </div>
                <div class="card-body">
                    <div id="target-achievement-chart" style="height: 250px;"></div>
                    <div class="mt-3 text-center">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-1 text-success">{{ $pesertaTargetTercapai }}</h6>
                                <small class="text-muted">Tercapai</small>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 text-warning">{{ $pesertaTargetBelum }}</h6>
                                <small class="text-muted">Belum</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="row">
        <!-- Internship Type Distribution -->
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Jenis Magang</h5>
                </div>
                <div class="card-body">
                    <div id="internship-type-chart" style="height: 280px;"></div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="mb-1 text-primary">{{ $magangKP }}</h6>
                                <small class="text-muted">Kerja Praktik</small>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-1 text-success">{{ $magangNasional }}</h6>
                                <small class="text-muted">Magang Nasional</small>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-1 text-warning">{{ $magangPenelitian }}</h6>
                                <small class="text-muted">Penelitian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-xl-4 col-md-6 mb-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Gender</h5>
                </div>
                <div class="card-body">
                    <div id="gender-chart" style="height: 250px;"></div>
                    <div class="mt-3 text-center">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-1 text-primary">{{ $pesertaLakiLaki }}</h6>
                                <small class="text-muted">Laki-laki</small>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 text-danger">{{ $pesertaPerempuan }}</h6>
                                <small class="text-muted">Perempuan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Summary -->
        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan Metrik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light-success rounded">
                                <div>
                                    <h6 class="mb-1">Rata-rata Peserta per Mentor</h6>
                                    <h4 class="mb-0 text-success">{{ $rataRataPesertaPerMentor }}</h4>
                                </div>
                                <div class="avtar avtar-xl bg-success">
                                    <i class="ti ti-users-group fs-1 text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light-primary rounded">
                                <div>
                                    <h6 class="mb-1">Total Tugas Tersedia</h6>
                                    <h4 class="mb-0 text-primary">{{ number_format($totalTugas) }}</h4>
                                </div>
                                <div class="avtar avtar-xl bg-primary">
                                    <i class="ti ti-clipboard-list fs-1 text-white"></i>
                                </div>
                            </div>
                        </div>
                        @if($mentorTertinggi)
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light-warning rounded">
                                <div>
                                    <h6 class="mb-1">Mentor Terbanyak Peserta</h6>
                                    <h5 class="mb-0 text-warning">{{ $mentorTertinggi->nama_mentor }}</h5>
                                    <small class="text-muted">{{ $mentorTertinggi->peserta_count }} peserta</small>
                                </div>
                                <div class="avtar avtar-xl bg-warning">
                                    <i class="ti ti-award fs-1 text-white"></i>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics Section -->
    <div class="row">
        <!-- Progress Level Distribution -->
        {{-- <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tingkat Progress Peserta</h5>
                </div>
                <div class="card-body">
                    <div id="progress-level-chart" style="height: 280px;"></div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="mb-1 text-danger">{{ $pesertaBaru }}</h6>
                                <small class="text-muted">Pemula (&lt;25%)</small>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-1 text-warning">{{ $pesertaMenungah }}</h6>
                                <small class="text-muted">Menengah (25-75%)</small>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-1 text-success">{{ $pesertaMahir }}</h6>
                                <small class="text-muted">Mahir (&gt;75%)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Top Institutions -->
        <div class="col-xl-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top 5 Asal Institusi</h5>
                </div>
                <div class="card-body">
                    <div id="institutions-chart" style="height: 280px;"></div>
                    <div class="mt-3">
                        @foreach($topInstitutions->take(3) as $index => $institution)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s me-2" style="background-color: {{ ['#5c92fe', '#2dce89', '#ffc107'][$index] }}20;">
                                    <span style="color: {{ ['#5c92fe', '#2dce89', '#ffc107'][$index] }};">{{ $index + 1 }}</span>
                                </div>
                                <span class="text-truncate" style="max-width: 250px;">{{ $institution->asal_instansi }}</span>
                            </div>
                            <span class="badge bg-light-primary">{{ $institution->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Completions Trend -->
        {{-- <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tren Penyelesaian Magang</h5>
                </div>
                <div class="card-body">
                    <div id="monthly-completion-chart" style="height: 280px;"></div>
                    <div class="mt-3 text-center">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-1 text-primary">{{ array_sum(array_column($monthlyCompletions, 'count')) }}</h6>
                                <small class="text-muted">Total Selesai (6 bulan)</small>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1 text-success">{{ count($monthlyCompletions) > 0 ? round(array_sum(array_column($monthlyCompletions, 'count')) / count($monthlyCompletions), 1) : 0 }}</h6>
                                <small class="text-muted">Rata-rata per Bulan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- ==================== NEW SECTION A: LINE/AREA CHARTS ==================== -->
    <div class="row">
        <!-- Daily Activity Trend -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-chart-line text-primary"></i> Tren Aktivitas Harian (30 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div id="daily-activity-chart"></div>
                </div>
            </div>
        </div>

        <!-- Attendance Heatmap -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-calendar-stats text-success"></i> Pola Aktivitas Peserta (30 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div id="attendance-heatmap-chart"></div>
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <small class="d-flex align-items-center">
                            <span class="badge bg-success me-1">●</span> Tinggi (>15 laporan)
                        </small>
                        <small class="d-flex align-items-center">
                            <span class="badge bg-warning me-1">●</span> Sedang (8-15)
                        </small>
                        <small class="d-flex align-items-center">
                            <span class="badge bg-danger me-1">●</span> Rendah (<8)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== NEW SECTION B: BAR CHARTS ==================== -->
    <div class="row">
        <!-- Mentor Performance -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-award text-warning"></i> Performa Mentor</h5>
                </div>
                <div class="card-body">
                    <div id="mentor-performance-chart"></div>
                </div>
            </div>
        </div>

        <!-- Task Categories Distribution -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-category text-info"></i> Distribusi Kategori Tugas</h5>
                </div>
                <div class="card-body">
                    <div id="task-categories-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== NEW SECTION C: ADDITIONAL PIE CHARTS ==================== -->
    <div class="row">
        {{-- <!-- Task Approval Status Detail -->
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-check-circle"></i> Status Persetujuan Tugas</h5>
                </div>
                <div class="card-body">
                    <div id="task-approval-detail-chart"></div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="ti ti-circle-filled text-success"></i> Disetujui</span>
                            <strong>{{ $tugasApproved }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="ti ti-circle-filled text-warning"></i> Pending</span>
                            <strong>{{ $tugasPending }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="ti ti-circle-filled text-danger"></i> Rejected</span>
                            <strong>{{ $tugasRejected }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Target Method Distribution -->
        <div class="col-xl-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-target-arrow"></i> Metode Penetapan Target Peserta</h5>
                </div>
                <div class="card-body">
                    <div id="target-method-chart"></div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="ti ti-circle-filled text-primary"></i> Berbasis SKS</span>
                            <strong>{{ $targetMethodSKS }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="ti ti-circle-filled text-info"></i> Manual</span>
                            <strong>{{ $targetMethodManual }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== NEW DATA TABLES & LISTS ==================== -->
    <div class="row">
        <!-- Recent Daily Reports -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-report text-primary"></i> Laporan Harian Terbaru</h5>
                    <a href="{{ route('laporan_harian.index') }}" class="btn btn-light-primary rounded">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Tugas</th>
                                    <th class="text-center">Progress</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDailyReports as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avtar avtar-s bg-light-primary me-2">
                                                <i class="ti ti-user"></i>
                                            </div>
                                            <span class="small">{{ $report['peserta_nama'] }}</span>
                                        </div>
                                    </td>
                                    <td><small>{{ $report['tugas'] }}</small></td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $report['progres'] == 100 ? 'success' : ($report['progres'] >= 50 ? 'warning' : 'secondary') }}">
                                            {{ $report['progres'] }}%
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $report['tanggal'] }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="ti ti-clipboard-off fs-3"></i>
                                        <p class="mb-0">Belum ada laporan harian</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-hourglass text-warning"></i> Menunggu Persetujuan</h5>
                    <span class="badge bg-warning">{{ count($pendingApprovals) }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Judul Tugas</th>
                                    <th>Peserta/Divisi</th>
                                    <th class="text-center">Bobot</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingApprovals as $pending)
                                <tr>
                                    <td>
                                        <span class="small fw-bold">{{ Str::limit($pending['judul'], 25) }}</span>
                                        <br><span class="badge badge-sm bg-light-{{ $pending['kategori'] == 'Individu' ? 'primary' : 'info' }}">{{ $pending['kategori'] }}</span>
                                    </td>
                                    <td><small>{{ Str::limit($pending['peserta'], 20) }}</small></td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $pending['beban_waktu'] }}j</span>
                                    </td>
                                    <td><small class="text-muted">{{ $pending['updated_at'] }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="ti ti-check-circle fs-3"></i>
                                        <p class="mb-0">Tidak ada tugas yang menunggu persetujuan</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Overdue Tasks & Low Performance Alert -->
    <div class="row">
        <!-- Overdue Tasks -->
        <div class="col-xl-6 col-md-12">
            <div class="card border-danger">
                <div class="card-header bg-light-danger d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-alert-circle text-danger"></i> Tugas Terlambat</h5>
                    <span class="badge bg-danger">{{ count($overdueTasks) }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Judul Tugas</th>
                                    <th>Peserta/Divisi</th>
                                    <th class="text-center">Deadline</th>
                                    <th class="text-center">Terlambat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overdueTasks as $overdue)
                                <tr>
                                    <td>
                                        <span class="small fw-bold">{{ Str::limit($overdue['judul'], 25) }}</span>
                                        <br><span class="badge badge-sm bg-{{ $overdue['status'] == 'Berlangsung' ? 'warning' : 'secondary' }}">{{ $overdue['status'] }}</span>
                                    </td>
                                    <td><small>{{ Str::limit($overdue['peserta'], 20) }}</small></td>
                                    <td class="text-center"><small class="text-danger">{{ $overdue['deadline'] }}</small></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $overdue['days_overdue'] }} hari</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="ti ti-circle-check fs-3 text-success"></i>
                                        <p class="mb-0">Tidak ada tugas yang terlambat</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Performance Alert -->
        <div class="col-xl-6 col-md-12">
            <div class="card border-warning">
                <div class="card-header bg-light-warning d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-alert-triangle text-warning"></i> Performa Rendah (<25%)</h5>
                    <span class="badge bg-warning">{{ count($lowPerformanceAlert) }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Bagian</th>
                                    <th class="text-center">Progress</th>
                                    <th class="text-center">Sisa Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowPerformanceAlert as $alert)
                                <tr>
                                    <td>
                                        <span class="small fw-bold">{{ Str::limit($alert['nama'], 20) }}</span>
                                        <br><small class="text-muted">{{ $alert['mentor'] }}</small>
                                    </td>
                                    <td><small>{{ $alert['bagian'] }}</small></td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                 style="width: {{ $alert['progress'] }}%"
                                                 aria-valuenow="{{ $alert['progress'] }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $alert['progress'] }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $alert['sisa_hari'] < 30 ? 'danger' : 'warning' }}">
                                            {{ $alert['sisa_hari'] }} hari
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="ti ti-trophy fs-3 text-success"></i>
                                        <p class="mb-0">Semua peserta menunjukkan performa baik!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Performance Insights -->
    <div class="row">
        <!-- Tingkat Keberhasilan -->
        <div class="col-xl-3 col-md-6">
            <x-stat-card
                class="card-grad-indigo"
                icon="ti-trending-up"
                title="Tingkat Keberhasilan"
                :value="$totalPeserta > 0 ? round(($pesertaTargetTercapai / $totalPeserta) * 100, 1) . '%' : '0%'"
                subtitle="Peserta mencapai target" />
        </div>

        <!-- Efisiensi Mentor -->
        <div class="col-xl-3 col-md-6">
            <x-stat-card
                class="card-grad-pink"
                icon="ti-users-group"
                title="Efisiensi Mentor"
                :value="$rataRataPesertaPerMentor"
                subtitle="Peserta per mentor" />
        </div>

        <!-- Penyelesaian Tugas -->
        <div class="col-xl-3 col-md-6">
            <x-stat-card
                class="card-grad-blue"
                icon="ti-clipboard-check"
                title="Penyelesaian Tugas"
                :value="$totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100, 1) . '%' : '0%'"
                subtitle="Tugas telah selesai" />
        </div>

        <!-- Laporan Akhir -->
        <div class="col-xl-3 col-md-6">
            <x-stat-card
                class="card-grad-rose"
                icon="ti-report-analytics"
                title="Laporan Akhir"
                :value="$totalPeserta > 0 ? round(($laporanAkhirSelesai / $totalPeserta) * 100, 1) . '%' : '0%'"
                subtitle="Telah menyelesaikan" />
        </div>
    </div>

    <!-- Recommendations -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-bulb text-warning"></i> Rekomendasi Berdasarkan Data</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-primary" role="alert">
                                <h6 class="alert-heading"><i class="ti ti-trending-up"></i> Peningkatan Partisipasi</h6>
                                @if($pesertaBaru > $pesertaMahir)
                                <p class="mb-0">Terdapat <strong>{{ $pesertaBaru }}</strong> peserta baru. Pertimbangkan untuk memberikan mentoring intensif atau pelatihan tambahan.</p>
                                @else
                                <p class="mb-0">Distribusi progress peserta cukup baik. Pertahankan kualitas pembimbingan saat ini.</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success" role="alert">
                                <h6 class="alert-heading"><i class="ti ti-target"></i> Optimasi Sumber Daya</h6>
                                @if($rataRataPesertaPerMentor > 5)
                                <p class="mb-0">Beban mentor cukup tinggi (<strong>{{ $rataRataPesertaPerMentor }}</strong> peserta/mentor). Pertimbangkan penambahan mentor.</p>
                                @else
                                <p class="mb-0">Rasio mentor-peserta optimal. Fokuskan pada peningkatan kualitas bimbingan.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avtar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    font-weight: 500;
}

.avtar-s {
    width: 2rem;
    height: 2rem;
    font-size: 0.875rem;
}

.avtar-xl {
    width: 4rem;
    height: 4rem;
    font-size: 1.5rem;
}

.bg-light-primary { background-color: rgba(92, 146, 254, 0.1) !important; }
.bg-light-info { background-color: rgba(32, 166, 231, 0.1) !important; }
.bg-light-success { background-color: rgba(45, 206, 137, 0.1) !important; }
.bg-light-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
.bg-light-secondary { background-color: rgba(108, 117, 125, 0.1) !important; }

.progress {
    border-radius: 10px;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 10px;
}

.card {
    border: 1px solid #e9ecef;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #6c757d;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<!-- ApexCharts -->
<script src="{{ asset('template/dist/assets/js/plugins/apexcharts.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trend Chart
    const monthlyTrendOptions = {
        series: [{
            name: 'Pendaftaran',
            data: @json(array_column($monthlyTrend, 'count'))
        }],
        chart: {
            type: 'area',
            height: 400,
            toolbar: { show: false }
        },
        colors: ['#5c92fe'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: @json(array_column($monthlyTrend, 'month')),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            title: { text: 'Jumlah Peserta' }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#monthly-trend-chart"), monthlyTrendOptions).render();

    // Status Donut Chart
    const statusDonutOptions = {
        series: [{{ $pesertaAktif }}, {{ $pesertaHampirSelesai }}, {{ $pesertaSelesai }}],
        chart: {
            type: 'donut',
            height: 200
        },
        colors: ['#5c92fe', '#ffc107', '#2dce89'],
        labels: ['Aktif', 'Dibawah 50%', 'Diatas 50%'],
        legend: {
            position: 'bottom',
            fontSize: '12px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#status-donut-chart"), statusDonutOptions).render();

    // Department Chart
    const departmentOptions = {
        series: [{
            name: 'Peserta',
            data: @json($bagianDistribution->pluck('peserta_count')->toArray())
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#2dce89'],
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            offsetX: -6,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        xaxis: {
            categories: @json($bagianDistribution->pluck('nama_bagian')->toArray()),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            title: { text: 'Bagian' }
        },
        grid: {
            borderColor: '#f1f1f1'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#department-chart"), departmentOptions).render();

    // Laporan Akhir Chart
    const laporanAkhirOptions = {
        series: [{{ $laporanAkhirSelesai }}, {{ $laporanAkhirBelum }}],
        chart: {
            type: 'pie',
            height: 250
        },
        colors: ['#2dce89', '#ffc107'],
        labels: ['Selesai', 'Belum Selesai'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#laporan-akhir-chart"), laporanAkhirOptions).render();

    // Task Status Chart
    const taskStatusOptions = {
        series: [{{ $tugasSelesai }}, {{ $tugasBerjalan }}, {{ $tugasBelumDimulai }}],
        chart: {
            type: 'pie',
            height: 250
        },
        colors: ['#2dce89', '#ffc107', '#dc3545'],
        labels: ['Selesai', 'Berlangsung', 'Belum Dimulai'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " tugas"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#task-status-chart"), taskStatusOptions).render();

    // Gender Distribution Chart
    const genderOptions = {
        series: [{{ $pesertaLakiLaki }}, {{ $pesertaPerempuan }}],
        chart: {
            type: 'donut',
            height: 250
        },
        colors: ['#5c92fe', '#e83e8c'],
        labels: ['Laki-laki', 'Perempuan'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function () {
                                return {{ $totalPeserta }}
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#gender-chart"), genderOptions).render();

    // Target Achievement Chart
    const targetAchievementOptions = {
        series: [{{ $pesertaTargetTercapai }}, {{ $pesertaTargetBelum }}],
        chart: {
            type: 'donut',
            height: 250
        },
        colors: ['#2dce89', '#ffc107'],
        labels: ['Target Tercapai', 'Belum Tercapai'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Progress',
                            formatter: function () {
                                const percentage = {{ $totalPeserta > 0 ? round(($pesertaTargetTercapai / $totalPeserta) * 100, 1) : 0 }};
                                return percentage + '%'
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#target-achievement-chart"), targetAchievementOptions).render();

    // Internship Type Chart
    const internshipTypeOptions = {
        series: [{{ $magangKP }}, {{ $magangNasional }}, {{ $magangPenelitian }}],
        chart: {
            type: 'pie',
            height: 280
        },
        colors: ['#5c92fe', '#2dce89', '#ffc107'],
        labels: ['Kerja Praktik', 'Magang Nasional', 'Penelitian'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#internship-type-chart"), internshipTypeOptions).render();

    // Task Approval Chart
    const taskApprovalOptions = {
        series: [{{ $tugasApproved }}, {{ $tugasPendingApproval }}],
        chart: {
            type: 'donut',
            height: 280
        },
        colors: ['#2dce89', '#ffc107'],
        labels: ['Disetujui', 'Menunggu Persetujuan'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Tugas',
                            formatter: function () {
                                return {{ $tugasApproved + $tugasPendingApproval }}
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " tugas"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#task-approval-chart"), taskApprovalOptions).render();

    // Progress Level Chart
    const progressLevelOptions = {
        series: [{{ $pesertaBaru }}, {{ $pesertaMenungah }}, {{ $pesertaMahir }}],
        chart: {
            type: 'donut',
            height: 280
        },
        colors: ['#dc3545', '#ffc107', '#2dce89'],
        labels: ['Pemula (<25%)', 'Menengah (25-75%)', 'Mahir (>75%)'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Peserta',
                            formatter: function () {
                                return {{ $totalPeserta }}
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#progress-level-chart"), progressLevelOptions).render();

    // Top Institutions Chart
    const institutionsOptions = {
        series: [{
            name: 'Jumlah Peserta',
            data: @json($topInstitutions->pluck('count')->toArray())
        }],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false }
        },
        colors: ['#5c92fe'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                dataLabels: {
                    position: 'top'
                },
                columnWidth: '60%'
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ['#5c92fe']
            }
        },
        xaxis: {
            categories: @json($topInstitutions->pluck('asal_instansi')->map(function($name) { return Str::limit($name, 15); })->toArray()),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                rotate: -45,
                style: {
                    fontSize: '10px'
                }
            }
        },
        yaxis: {
            title: { text: 'Jumlah Peserta' }
        },
        grid: {
            borderColor: '#f1f1f1'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#institutions-chart"), institutionsOptions).render();

    // Monthly Completion Chart
    const monthlyCompletionOptions = {
        series: [{
            name: 'Peserta Selesai',
            data: @json(array_column($monthlyCompletions, 'count'))
        }],
        chart: {
            type: 'line',
            height: 280,
            toolbar: { show: false }
        },
        colors: ['#2dce89'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 6,
            colors: ['#2dce89'],
            strokeColors: '#fff',
            strokeWidth: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#2dce89'],
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.1
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json(array_column($monthlyCompletions, 'month')),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            title: { text: 'Jumlah Peserta' }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta selesai"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#monthly-completion-chart"), monthlyCompletionOptions).render();

    // ==================== NEW CHARTS SECTION A: LINE/AREA CHARTS ====================

    // Daily Activity Trend Chart
    const dailyActivityOptions = {
        series: [{
            name: 'Laporan Harian',
            data: @json(array_column($dailyActivityTrend, 'count'))
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#5c92fe'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: @json(array_column($dailyActivityTrend, 'date')),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                rotate: -45,
                style: { fontSize: '10px' }
            }
        },
        yaxis: {
            title: { text: 'Jumlah Laporan' }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " laporan"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#daily-activity-chart"), dailyActivityOptions).render();

    // Attendance Heatmap Chart
    const attendanceHeatmapOptions = {
        series: @json($attendanceHeatmapData),
        chart: {
            type: 'heatmap',
            height: 300,
            toolbar: { show: false }
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#2dce89'],
        plotOptions: {
            heatmap: {
                shadeIntensity: 0.5,
                radius: 4,
                useFillColorAsStroke: false,
                colorScale: {
                    ranges: [
                        {
                            from: 0,
                            to: 7,
                            color: '#dc3545',
                            name: 'Rendah'
                        },
                        {
                            from: 8,
                            to: 15,
                            color: '#ffc107',
                            name: 'Sedang'
                        },
                        {
                            from: 16,
                            to: 100,
                            color: '#2dce89',
                            name: 'Tinggi'
                        }
                    ]
                }
            }
        },
        xaxis: {
            type: 'category',
            categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' laporan harian'
                }
            }
        },
        legend: {
            show: false
        }
    };
    new ApexCharts(document.querySelector("#attendance-heatmap-chart"), attendanceHeatmapOptions).render();

    // ==================== NEW CHARTS SECTION B: BAR CHARTS ====================

    // Mentor Performance Chart (Grouped Bar)
    const mentorPerformanceOptions = {
        series: [
            {
                name: 'Total Peserta',
                data: @json($mentorPerformance->pluck('total_peserta')->toArray())
            },
            {
                name: 'Selesai',
                data: @json($mentorPerformance->pluck('completed')->toArray())
            }
        ],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#5c92fe', '#2dce89'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: @json($mentorPerformance->pluck('nama')->toArray()),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                rotate: -45,
                style: { fontSize: '10px' }
            }
        },
        yaxis: {
            title: { text: 'Jumlah Peserta' }
        },
        grid: {
            borderColor: '#f1f1f1'
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#mentor-performance-chart"), mentorPerformanceOptions).render();

    // Task Categories Distribution Chart
    const taskCategoriesOptions = {
        series: [
            {
                name: 'Total Tugas',
                data: [{{ $taskIndividu }}, {{ $taskDivisi }}]
            },
            {
                name: 'Selesai',
                data: [{{ $taskIndividuSelesai }}, {{ $taskDivisiSelesai }}]
            }
        ],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        colors: ['#ffc107', '#2dce89'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%',
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ['#304758']
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Individu', 'Divisi'],
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            title: { text: 'Jumlah Tugas' }
        },
        grid: {
            borderColor: '#f1f1f1'
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " tugas"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#task-categories-chart"), taskCategoriesOptions).render();

    // ==================== NEW CHARTS SECTION C: PIE/DONUT ADDITIONS ====================

    // Task Approval Detail Chart - COMMENTED: Variable $tugasRejected not defined
    /*
    const taskApprovalDetailOptions = {
        series: [{{ $tugasApproved }}, {{ $tugasPendingApproval }}, 0],
        chart: {
            type: 'donut',
            height: 250
        },
        colors: ['#2dce89', '#ffc107', '#dc3545'],
        labels: ['Disetujui', 'Pending', 'Rejected'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " tugas"
                }
            }
        }
    };
    // new ApexCharts(document.querySelector("#task-approval-detail-chart"), taskApprovalDetailOptions).render();
    */

    // Target Method Distribution Chart
    const targetMethodOptions = {
        series: [{{ $targetMethodSKS }}, {{ $targetMethodManual }}],
        chart: {
            type: 'pie',
            height: 250
        },
        colors: ['#5c92fe', '#20a7e7'],
        labels: ['Berbasis SKS', 'Manual'],
        legend: {
            position: 'bottom',
            fontSize: '11px'
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                return opts.w.config.series[opts.seriesIndex] + ' (' + Math.round(val) + '%)'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " peserta"
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#target-method-chart"), targetMethodOptions).render();

    // ==================== LAZY LOAD HELPER ====================
    function renderWhenVisible(selector, options) {
        const el = document.querySelector(selector);
        if (!el) return;

        const start = () => new ApexCharts(el, options).render();

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries, obs) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        start();
                        obs.disconnect();
                    }
                });
            }, { rootMargin: '100px' });
            io.observe(el);
        } else {
            start();
        }
    }

    // ==================== SAFE DONUT HELPER ====================
    function safeDonut(series, opts = {}) {
        const total = series.reduce((a, b) => a + b, 0);
        const safeSeries = total === 0 ? series.map(() => 0) : series;

        return {
            ...opts,
            series: safeSeries,
            dataLabels: { enabled: total !== 0 },
            tooltip: {
                y: {
                    formatter: val => val + (opts.unit || '')
                }
            },
            noData: {
                text: total === 0 ? 'Tidak ada data' : undefined
            }
        };
    }

    // Filter Enhancement
    const tahunSelect = document.querySelector('select[name="tahun"]');
    const bulanSelect = document.getElementById('bulanFilter');

    if (tahunSelect && bulanSelect) {
        // Disable bulan if tahun not selected
        function toggleBulan() {
            if (!tahunSelect.value) {
                bulanSelect.disabled = true;
                bulanSelect.value = '';
            } else {
                bulanSelect.disabled = false;
            }
        }
        toggleBulan();
        tahunSelect.addEventListener('change', toggleBulan);
    }
});
</script>
@endpush
@endsection
