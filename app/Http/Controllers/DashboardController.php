<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Peserta;
use App\Models\Mentor;
use App\Models\Bagian;
use App\Models\User;
use App\Models\Penugasan;
use App\Models\Laporan;
use App\Models\LaporanAkhir;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Redirect based on user role
        if ($user->role_id == 1) { // Admin
            return $this->adminDashboard($request);
        } elseif ($user->role_id == 2) { // Mentor
            return $this->mentorDashboard();
        } else { // Peserta
            return $this->pesertaDashboard();
        }
    }

    public function adminDashboard(Request $request)
    {
        // Get filter parameters
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $search = $request->input('search');

        // Build query with filters
        $pesertaQuery = Peserta::query();

        // Filter berdasarkan tahun
        if ($tahun) {
            $pesertaQuery->whereYear('created_at', $tahun);

            // Filter bulan hanya jika tahun juga dipilih
            if ($bulan) {
                $pesertaQuery->whereMonth('created_at', $bulan);
            }
        }

        // Filter berdasarkan search
        if ($search) {
            $pesertaQuery->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nomor_identitas', 'like', '%' . $search . '%')
                  ->orWhere('asal_instansi', 'like', '%' . $search . '%');
            });
        }

        // Basic Statistics (with filters)
        $totalPeserta = (clone $pesertaQuery)->count();
        $totalMentor = Mentor::count();
        $totalBagian = Bagian::count();
        $totalUsers = User::count();

        // Peserta Status (with filters)
        $pesertaSelesai = (clone $pesertaQuery)->whereHas('laporanAkhir', function($q) {
            $q->where('status', 'terima');
        })->count();
        $pesertaAktif = (clone $pesertaQuery)->whereDoesntHave('laporanAkhir', function($q) {
            $q->where('status', 'terima');
        })->count();

        // Peserta dengan progress < 50%
        $pesertaHampirSelesai = (clone $pesertaQuery)->get()->filter(function($peserta) {
            // Hitung target berdasarkan metode
            $target = $peserta->target_method === 'sks'
                ? ($peserta->sks * 45)
                : $peserta->target_waktu_tugas;

            // Jika target 0, skip peserta ini
            if ($target == 0) return false;

            // Hitung progress percentage
            $progress = ($peserta->waktu_tugas_tercapai / $target) * 100;

            // Filter peserta dengan progress < 50%
            return $progress < 50;
        })->count();

        // Recent Peserta Selesai (last 5) - Peserta dengan laporan akhir diterima
        $recentPeserta = (clone $pesertaQuery)
            ->with(['bagian', 'mentor', 'laporanAkhir' => function($query) {
                $query->where('status', 'terima')->latest();
            }])
            ->whereHas('laporanAkhir', function($q) {
                $q->where('status', 'terima');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($peserta) {
                // Calculate target based on method
                $target = $peserta->target_method === 'sks'
                    ? ($peserta->sks * 45)
                    : $peserta->target_waktu_tugas;

                // Calculate progress percentage
                $completedHours = $peserta->waktu_tugas_tercapai ?? 0;
                $peserta->progress_percentage = $target > 0 ? round(($completedHours / $target) * 100, 1) : 0;

                // Get tahun masuk (year from tanggal_mulai_magang)
                $peserta->tahun_magang = $peserta->tanggal_mulai_magang
                    ? Carbon::parse($peserta->tanggal_mulai_magang)->format('Y')
                    : '-';

                // Get tanggal selesai (tanggal laporan akhir diterima)
                $laporanAkhirDiterima = $peserta->laporanAkhir->where('status', 'terima')->first();
                $peserta->tanggal_selesai = $laporanAkhirDiterima
                    ? Carbon::parse($laporanAkhirDiterima->updated_at)->format('d M Y')
                    : '-';

                return $peserta;
            });

        // Upcoming Completions (next 30 days) with filters
        // $upcomingCompletions = (clone $pesertaQuery)->with('bagian')
        //     ->whereBetween('tanggal_selesai_magang', [now(), now()->addDays(30)])
        //     ->orderBy('tanggal_selesai_magang', 'asc')
        //     ->limit(5)
        //     ->get();

        // Department Distribution
        $bagianDistribution = Bagian::withCount('peserta')->get();

        // Monthly Registration Trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Peserta::whereYear('created_at', $month->year)
                           ->whereMonth('created_at', $month->month)
                           ->count();
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Total working hours across all peserta
        $totalJamMagang = Peserta::sum('waktu_tugas_tercapai') ?? 0;

        // Laporan Akhir Statistics - Hanya yang statusnya 'terima'
        $laporanAkhirSelesai = (clone $pesertaQuery)->whereHas('laporanAkhir', function($q) {
            $q->where('status', 'terima');
        })->count();
        $laporanAkhirBelum = $totalPeserta - $laporanAkhirSelesai;

        // Task Completion Statistics
        $totalTugas = Penugasan::count();
        $tugasSelesai = Penugasan::where('status_tugas', 'Selesai')->count();
        $tugasBerjalan = Penugasan::where('status_tugas', 'Dikerjakan')->count();
        $tugasBelumDimulai = Penugasan::where('status_tugas', 'Belum')->count();

        // Peserta Progress Statistics
        $pesertaTargetTercapai = Peserta::whereRaw('waktu_tugas_tercapai >= target_waktu_tugas')->count();
        $pesertaTargetBelum = $totalPeserta - $pesertaTargetTercapai;

        // Gender Distribution
        $pesertaLakiLaki = Peserta::where('jenis_kelamin', 'Laki-laki')->count();
        $pesertaPerempuan = Peserta::where('jenis_kelamin', 'Perempuan')->count();

        // Internship Type Distribution
        $magangKP = Peserta::where('tipe_magang', 'Kerja Praktik')->count();
        $magangNasional = Peserta::where('tipe_magang', 'Magang Nasional')->count();
        $magangPenelitian = Peserta::where('tipe_magang', 'Penelitian')->count();

        // Task Approval Statistics
        $tugasApproved = Penugasan::where('is_approved', 1)->count();
        // Pending Approval: tugas yang sudah selesai tapi belum di-approve
        $tugasPendingApproval = Penugasan::where('is_approved', 0)->where('status_tugas', 'Selesai')->count();

        // Mentor Workload Statistics
        $mentorTertinggi = Mentor::withCount('peserta')->orderBy('peserta_count', 'desc')->first();
        $rataRataPesertaPerMentor = $totalMentor > 0 ? round($totalPeserta / $totalMentor, 1) : 0;

        // Progress Distribution (untuk pie chart tambahan)
        $pesertaBaru = Peserta::whereRaw('waktu_tugas_tercapai < (target_waktu_tugas * 0.25)')->count(); // < 25%
        $pesertaMenungah = Peserta::whereRaw('waktu_tugas_tercapai >= (target_waktu_tugas * 0.25) AND waktu_tugas_tercapai < (target_waktu_tugas * 0.75)')->count(); // 25-75%
        $pesertaMahir = Peserta::whereRaw('waktu_tugas_tercapai >= (target_waktu_tugas * 0.75)')->count(); // >= 75%

        // Monthly Performance (peserta selesai per bulan dalam 6 bulan terakhir)
        $monthlyCompletions = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Peserta::whereYear('tanggal_selesai_magang', $month->year)
                           ->whereMonth('tanggal_selesai_magang', $month->month)
                           ->where('tanggal_selesai_magang', '<', now())
                           ->count();
            $monthlyCompletions[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Institution Distribution (top 5)
        $topInstitutions = Peserta::selectRaw('asal_instansi, COUNT(*) as count')
            ->groupBy('asal_instansi')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Age Groups (berdasarkan tahun lahir atau estimasi dari nomor identitas)
        $pesertaMuda = Peserta::where('created_at', '>=', now()->subYears(22))->count(); // Estimasi umur < 22
        $pesertaDewasa = Peserta::where('created_at', '<', now()->subYears(22))
                              ->where('created_at', '>=', now()->subYears(25))->count(); // 22-25
        $pesertaTua = Peserta::where('created_at', '<', now()->subYears(25))->count(); // > 25

        // ==================== SECTION A: LINE/AREA CHARTS ====================

        // Daily Activity Trend (last 30 days)
        $dailyActivityTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = \App\Models\LaporanHarian::whereDate('created_at', $date->format('Y-m-d'))->count();
            $dailyActivityTrend[] = [
                'date' => $date->format('d M'),
                'count' => $count
            ];
        }

        // Attendance Heatmap (last 30 days - weekly view)
        $attendanceHeatmapData = [];
        $startDate = now()->subDays(34); // Start from 35 days ago to get 5 complete weeks

        // Mapping hari dalam bahasa Indonesia (singkatan)
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        // Generate 5 weeks of data
        for ($week = 0; $week < 5; $week++) {
            $weekData = [
                'name' => 'Week ' . ($week + 1),
                'data' => []
            ];

            // Generate 7 days for each week
            for ($day = 0; $day < 7; $day++) {
                $currentDate = $startDate->copy()->addDays($week * 7 + $day);

                // Count daily reports for this date
                $count = \App\Models\LaporanHarian::whereDate('created_at', $currentDate->format('Y-m-d'))
                    ->count();

                $dayOfWeek = $currentDate->dayOfWeek; // 0 (Minggu) sampai 6 (Sabtu)

                $weekData['data'][] = [
                    'x' => $dayNames[$dayOfWeek],
                    'y' => $count
                ];
            }

            $attendanceHeatmapData[] = $weekData;
        }

        // ==================== SECTION B: BAR CHARTS ====================

        // Mentor Performance (jumlah peserta & completion rate per mentor)
        $mentorPerformance = Mentor::withCount(['peserta' => function($query) {
            $query->where('tanggal_selesai_magang', '<', now());
        }])
        ->with(['peserta' => function($query) {
            $query->select('id', 'mentor_id', 'waktu_tugas_tercapai', 'target_waktu_tugas', 'target_method', 'sks');
        }])
        ->get()
        ->map(function($mentor) {
            $totalPeserta = $mentor->peserta->count();
            $completedTasks = 0;

            foreach ($mentor->peserta as $peserta) {
                $target = $peserta->target_method === 'sks' ? ($peserta->sks * 45) : $peserta->target_waktu_tugas;
                if ($peserta->waktu_tugas_tercapai >= $target) {
                    $completedTasks++;
                }
            }

            return [
                'nama' => $mentor->nama_mentor,
                'total_peserta' => $totalPeserta,
                'completed' => $completedTasks,
                'completion_rate' => $totalPeserta > 0 ? round(($completedTasks / $totalPeserta) * 100, 1) : 0
            ];
        })
        ->sortByDesc('total_peserta')
        ->take(10)
        ->values();

        // Task Categories Distribution
        $taskIndividu = Penugasan::where('kategori', 'Individu')->count();
        $taskDivisi = Penugasan::where('kategori', 'Divisi')->count();
        $taskIndividuSelesai = Penugasan::where('kategori', 'Individu')->where('status_tugas', 'Selesai')->count();
        $taskDivisiSelesai = Penugasan::where('kategori', 'Divisi')->where('status_tugas', 'Selesai')->count();

        // ==================== SECTION C: PIE/DONUT CHARTS (ADDITIONS) ====================

        // Task Approval Detailed
        $tugasApproved = Penugasan::where('is_approved', 1)->where('status_tugas', 'Selesai')->count();
        // Pending: tugas yang belum di-approve DAN sudah selesai (menunggu approval)
        $tugasPending = Penugasan::where('is_approved', 0)->where('status_tugas', 'Selesai')->count();

        // Target Method Distribution
        $targetMethodSKS = Peserta::where('target_method', 'sks')->count();
        // Manual = semua yang bukan SKS (termasuk NULL, empty, atau value lain)
        $targetMethodManual = $totalPeserta - $targetMethodSKS;

        // ==================== DATA TABLES & LISTS ====================

        // Recent Daily Reports (last 10)
        $recentDailyReports = \App\Models\LaporanHarian::with(['peserta.user', 'penugasan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($laporan) {
                return [
                    'peserta_nama' => $laporan->peserta->nama_lengkap ?? 'N/A',
                    'tugas' => Str::limit($laporan->penugasan->judul_tugas ?? 'N/A', 30),
                    'progres' => $laporan->progres_tugas,
                    'tanggal' => $laporan->created_at->format('d M Y H:i'),
                    'status' => $laporan->status_tugas
                ];
            });

        // Pending Approvals (tasks waiting for approval)
        $pendingApprovals = Penugasan::with(['peserta.user', 'bagian'])
            ->where('status_tugas', 'Selesai')
            ->where('is_approved', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($tugas) {
                return [
                    'id' => $tugas->id,
                    'judul' => $tugas->judul_tugas,
                    'peserta' => $tugas->kategori === 'Individu'
                        ? ($tugas->peserta->nama_lengkap ?? 'N/A')
                        : 'Divisi ' . ($tugas->bagian->nama_bagian ?? 'N/A'),
                    'kategori' => $tugas->kategori,
                    'beban_waktu' => $tugas->beban_waktu,
                    'updated_at' => $tugas->updated_at->diffForHumans()
                ];
            });

        // Overdue Tasks (tasks past deadline and not completed)
        $overdueTasks = Penugasan::with(['peserta.user', 'bagian'])
            ->where('deadline', '<', now())
            ->where('status_tugas', '!=', 'Selesai')
            ->orderBy('deadline', 'asc')
            ->limit(10)
            ->get()
            ->map(function($tugas) {
                return [
                    'id' => $tugas->id,
                    'judul' => $tugas->judul_tugas,
                    'peserta' => $tugas->kategori === 'Individu'
                        ? ($tugas->peserta->nama_lengkap ?? 'N/A')
                        : 'Divisi ' . ($tugas->bagian->nama_bagian ?? 'N/A'),
                    'kategori' => $tugas->kategori,
                    'deadline' => $tugas->deadline->format('d M Y'),
                    'days_overdue' => now()->diffInDays($tugas->deadline),
                    'status' => $tugas->status_tugas
                ];
            });

        // Low Performance Alert (peserta with progress < 25%)
        $lowPerformanceAlert = Peserta::with(['bagian', 'mentor'])
            ->get()
            ->filter(function($peserta) {
                $target = $peserta->target_method === 'sks'
                    ? ($peserta->sks * 45)
                    : $peserta->target_waktu_tugas;

                if ($target == 0) return false;

                $progress = ($peserta->waktu_tugas_tercapai / $target) * 100;
                return $progress < 25 && $peserta->tanggal_selesai_magang > now();
            })
            ->map(function($peserta) {
                $target = $peserta->target_method === 'sks'
                    ? ($peserta->sks * 45)
                    : $peserta->target_waktu_tugas;
                $progress = $target > 0 ? round(($peserta->waktu_tugas_tercapai / $target) * 100, 1) : 0;

                return [
                    'id' => $peserta->id,
                    'nama' => $peserta->nama_lengkap,
                    'bagian' => $peserta->bagian->nama_bagian ?? 'N/A',
                    'mentor' => $peserta->mentor->nama_mentor ?? 'N/A',
                    'progress' => $progress,
                    'waktu_tercapai' => $peserta->waktu_tugas_tercapai,
                    'target' => $target,
                    'sisa_hari' => now()->diffInDays($peserta->tanggal_selesai_magang)
                ];
            })
            ->sortBy('progress')
            ->take(10)
            ->values();

        // Get list of years for filter dropdown
        $tahunList = Peserta::selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('dashboard.admin', compact(
            'totalPeserta', 'totalMentor', 'totalBagian', 'totalUsers',
            'pesertaAktif', 'pesertaSelesai', 'pesertaHampirSelesai',
            'recentPeserta', 'bagianDistribution',
            'monthlyTrend', 'totalJamMagang',
            // Pie Chart Data
            'laporanAkhirSelesai', 'laporanAkhirBelum',
            'totalTugas', 'tugasSelesai', 'tugasBerjalan', 'tugasBelumDimulai',
            'pesertaTargetTercapai', 'pesertaTargetBelum',
            'pesertaLakiLaki', 'pesertaPerempuan',
            'magangKP', 'magangNasional', 'magangPenelitian',
            'tugasApproved', 'tugasPendingApproval',
            'mentorTertinggi', 'rataRataPesertaPerMentor',
            // Additional Analytics
            'pesertaBaru', 'pesertaMenungah', 'pesertaMahir',
            'monthlyCompletions', 'topInstitutions',
            'pesertaMuda', 'pesertaDewasa', 'pesertaTua',
            // Filter data
            'tahunList',
            // NEW: Section A - Line/Area Charts
            'dailyActivityTrend', 'attendanceHeatmapData',
            // NEW: Section B - Bar Charts
            'mentorPerformance', 'taskIndividu', 'taskDivisi',
            'taskIndividuSelesai', 'taskDivisiSelesai',
            // NEW: Section C - Pie Charts Additions
            'tugasPending',
            'targetMethodSKS', 'targetMethodManual',
            // NEW: Data Tables & Lists
            'recentDailyReports', 'pendingApprovals', 'overdueTasks', 'lowPerformanceAlert'
        ));
    }

    public function mentorDashboard()
    {
        // Implementation for mentor dashboard
        return view('dashboard.mentor');
    }

    public function pesertaDashboard()
    {
        // Implementation for peserta dashboard
        return view('dashboard.peserta');
    }
}
