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
        $pesertaAktif = (clone $pesertaQuery)->where('tanggal_selesai_magang', '>=', now())->count();
        $pesertaSelesai = (clone $pesertaQuery)->where('tanggal_selesai_magang', '<', now())->count();
        $pesertaHampirSelesai = (clone $pesertaQuery)->whereBetween('tanggal_selesai_magang', [
            now(), now()->addDays(14)
        ])->count();

        // Recent Peserta (last 5) with filters
        $recentPeserta = (clone $pesertaQuery)->with(['bagian', 'mentor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($peserta) {
                // Calculate progress percentage
                $totalHours = $peserta->target_waktu_tugas ?? 400; // Default 400 hours
                $completedHours = $peserta->waktu_tugas_tercapai ?? 0;
                $peserta->progress_percentage = $totalHours > 0 ? round(($completedHours / $totalHours) * 100, 1) : 0;
                return $peserta;
            });

        // Upcoming Completions (next 30 days) with filters
        $upcomingCompletions = (clone $pesertaQuery)->with('bagian')
            ->whereBetween('tanggal_selesai_magang', [now(), now()->addDays(30)])
            ->orderBy('tanggal_selesai_magang', 'asc')
            ->limit(5)
            ->get();

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

        // Laporan Akhir Statistics
        $laporanAkhirSelesai = Peserta::whereHas('laporanAkhir')->count();
        $laporanAkhirBelum = $totalPeserta - $laporanAkhirSelesai;

        // Task Completion Statistics
        $totalTugas = Penugasan::count();
        $tugasSelesai = Penugasan::where('status_tugas', 'Selesai')->count();
        $tugasBerjalan = Penugasan::where('status_tugas', 'Berlangsung')->count();
        $tugasBelumDimulai = Penugasan::where('status_tugas', 'Belum Dimulai')->count();

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

        // Get list of years for filter dropdown
        $tahunList = Peserta::selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('dashboard.admin', compact(
            'totalPeserta', 'totalMentor', 'totalBagian', 'totalUsers',
            'pesertaAktif', 'pesertaSelesai', 'pesertaHampirSelesai',
            'recentPeserta', 'upcomingCompletions', 'bagianDistribution',
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
            'tahunList'
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
