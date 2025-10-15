<?php

namespace App\Http\Controllers;

use App\Models\LaporanAkhir;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class LaporanAkhirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            // Tolak akses jika pengguna BUKAN Peserta, BUKAN Admin, DAN BUKAN Mentor.
            if (!$user->isPeserta() && !$user->isAdmin() && !$user->isMentor()) {
                abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI HAK AKSES.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isPeserta()) {
            $this->checkPesertaBisaLaporanAkhir();
        }

        $laporanAkhir = collect();

        if ($user->isAdmin()) {
            $laporanAkhir = LaporanAkhir::with(['peserta', 'mentor'])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($user->isMentor()) {
            $laporanAkhir = LaporanAkhir::whereHas('peserta', function($query) use ($user) {
                $query->where('mentor_id', $user->mentor->id);
            })
            ->with(['peserta', 'mentor'])
            ->orderBy('id', 'desc')
            ->get();
        } elseif ($user->isPeserta()) {
            $laporanAkhir = LaporanAkhir::where('peserta_id', $user->peserta->id)
                ->with(['peserta', 'mentor'])
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('laporan_akhir.index', compact('laporanAkhir'));
    }

    public function create()
    {
        if (!Auth::user()->isPeserta()) {
        abort(403, 'AKSES DITOLAK: HANYA PESERTA YANG BISA MEMBUAT LAPORAN AKHIR.');
        }

        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            abort(403, 'ANDA TIDAK TERDAFTAR SEBAGAI PESERTA.');
        }

        // Cek apakah laporan akhir sudah diterima
        if ($peserta->is_laporan_akhir_selesai) {
            Alert::success('Selamat!', 'Laporan akhir Anda sudah diterima. Magang telah selesai.');
            return redirect()->route('laporan-akhir.index');
        }

        // Cek apakah memenuhi syarat membuat laporan akhir
        if (!$peserta->bisa_laporan_akhir) {
            Alert::error('Tidak Dapat Membuat Laporan', 'Anda belum dapat membuat laporan akhir. Silakan selesaikan tugas terlebih dahulu.');
            return redirect()->route('laporan-akhir.index');
        }

        return view('laporan_akhir.create', compact('peserta'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isPeserta()) {
        abort(403, 'AKSES DITOLAK: HANYA PESERTA YANG BISA MEMBUAT LAPORAN AKHIR.');
        }

        $this->checkPesertaBisaLaporanAkhir();

        $user = Auth::user();
        $peserta = $user->peserta;

        $validated = $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'deskripsi_laporan' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $validated['peserta_id'] = $peserta->id;
        $validated['mentor_id'] = $peserta->mentor_id;
        $validated['status'] = 'draft';

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('laporan_akhir', 'public');
        }

        LaporanAkhir::create($validated);

        Alert::success('Berhasil', 'Laporan akhir berhasil disimpan.');
        return redirect()->route('laporan-akhir.index');
    }


    public function edit(LaporanAkhir $laporanAkhir)
    {
        $user = Auth::user();

        if (!$this->canAccessLaporan($user, $laporanAkhir, 'edit')) {
            abort(403, 'AKSES DITOLAK.');
        }

        if ($user->isPeserta()) {
            $this->checkPesertaBisaLaporanAkhir();

            if ($laporanAkhir->status !== 'draft') {
                abort(403, 'ANDA TIDAK DAPAT MENGEDIT LAPORAN YANG SUDAH DIVERIFIKASI.');
            }
        }

        return view('laporan_akhir.edit', compact('laporanAkhir'));
    }

    public function update(Request $request, LaporanAkhir $laporanAkhir)
    {
        $user = Auth::user();

        if (!$this->canAccessLaporan($user, $laporanAkhir, 'update')) {
            abort(403, 'AKSES DITOLAK.');
        }

        if ($user->isPeserta()) {
            $this->checkPesertaBisaLaporanAkhir();

            if ($laporanAkhir->status !== 'draft') {
                abort(403, 'ANDA TIDAK DAPAT MENGEDIT LAPORAN YANG SUDAH DIVERIFIKASI.');
            }
        }

        $validated = $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'deskripsi_laporan' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $oldFile = $laporanAkhir->file_path;

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('laporan_akhir', 'public');
        }

        $laporanAkhir->update($validated);

        if ($request->hasFile('file_path') && $oldFile) {
            Storage::disk('public')->delete($oldFile);
        }

        Alert::success('Berhasil', 'Laporan akhir berhasil diperbarui.');
        return redirect()->route('laporan-akhir.index');
    }

    public function show(LaporanAkhir $laporanAkhir)
    {
        $user = Auth::user();

        // Validasi akses
        if (!$this->canAccessLaporan($user, $laporanAkhir, 'view')) {
            abort(403, 'AKSES DITOLAK.');
        }

        return view('laporan_akhir.show', compact('laporanAkhir'));
    }

    public function destroy(LaporanAkhir $laporanAkhir)
    {
        $user = Auth::user();

        // Validasi akses hapus (hanya admin)
        if (!$user->isAdmin()) {
            abort(403, 'AKSES DITOLAK: HANYA ADMIN YANG BISA MENGHAPUS LAPORAN.');
        }

        // Hapus file jika ada
        if ($laporanAkhir->file_path) {
            Storage::disk('public')->delete($laporanAkhir->file_path);
        }

        $laporanAkhir->delete();

        Alert::success('Berhasil', 'Laporan akhir berhasil dihapus.');
        return redirect()->back();
    }

    public function updateStatus(Request $request, LaporanAkhir $laporanAkhir)
    {
        $user = Auth::user();

        // Hanya admin dan mentor yang bisa mengubah status
        if (!$user->isAdmin() && !$user->isMentor()) {
            abort(403, 'AKSES DITOLAK: HANYA ADMIN/MENTOR YANG BISA MENGUBAH STATUS.');
        }

        // Validasi input berdasarkan aksi
        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:draft,review,terima,tolak',
            ]);

            $laporanAkhir->update(['status' => $request->status]);

            $statusMessages = [
                'draft' => 'Status laporan dikembalikan ke "Draft". Peserta dapat mengedit laporan kembali.',
                'review' => 'Status laporan diubah ke "Review".',
                'terima' => 'Laporan akhir diterima.',
                'tolak' => 'Laporan akhir ditolak.'
            ];

            $message = $statusMessages[$request->status] ?? 'Status laporan berhasil diperbarui.';
        }

        // Update catatan mentor
        if ($request->has('catatan_mentor')) {
            $request->validate([
                'catatan_mentor' => 'nullable|string|max:1000',
            ]);

            $laporanAkhir->update(['catatan_mentor' => $request->catatan_mentor]);
            $message = 'Catatan berhasil disimpan.';
        }

        Alert::success('Berhasil', $message ?? 'Data berhasil diperbarui.');
        return redirect()->back();
    }

    private function canAccessLaporan($user, $laporan, $action = 'view')
    {
        // Admin bisa akses semua
        if ($user->isAdmin()) {
            return true;
        }

        // Mentor bisa akses laporan dari peserta yang dibimbingnya
        if ($user->isMentor() && $laporan->mentor_id === $user->mentor->id) {
            return true;
        }

        // Peserta hanya bisa akses laporannya sendiri
        if ($user->isPeserta() && $laporan->peserta_id === $user->peserta?->id) {
            // Untuk edit/update, cek apakah status masih draft
            if (in_array($action, ['edit', 'update']) && $laporan->status !== 'draft') {
                return false;
            }
            return true;
        }

        return false;
    }

    private function checkPesertaBisaLaporanAkhir()
    {
        $user = Auth::user();

        if ($user->isPeserta()) {
            $peserta = $user->peserta;

            if (!$peserta) {
                abort(403, 'Anda tidak terdaftar sebagai peserta.');
            }

            // Cek apakah laporan akhir sudah diterima
            if ($peserta->is_laporan_akhir_selesai) {
                Alert::success('Selamat!', 'Laporan akhir Anda sudah diterima. Magang telah selesai.');
                return redirect()->route('laporan-akhir.index');
            }

            // Cek apakah memenuhi syarat membuat laporan akhir
            if (!$peserta->bisa_laporan_akhir) {
                abort(403, 'Anda belum dapat membuat laporan akhir. Silakan selesaikan tugas terlebih dahulu.');
            }
        }
    }
}
