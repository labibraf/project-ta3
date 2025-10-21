# Perbaikan Dashboard Mentor

## Tanggal: 20 Oktober 2025

## Error yang Ditemukan

### Error 1: Column not found - `jenis_target_pencapaian`

**Error Message:**

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'jenis_target_pencapaian' in 'where clause'
```

**Penyebab:**
Controller menggunakan nama kolom yang salah. Struktur tabel `pesertas` yang benar adalah:

- `target_method` (enum: 'sks' atau 'manual') - BUKAN `jenis_target_pencapaian`
- `sks` (SKS tercapai/sekarang) - BUKAN `sks_sekarang`
- `target_waktu_tugas` (target waktu) - BUKAN `sks_target` atau `durasi_target`
- `waktu_tugas_tercapai` (waktu tercapai) - BUKAN `durasi_sekarang`

## Perbaikan yang Dilakukan

### 1. Update Query: Peserta Performa Rendah

**SEBELUM:**

```php
$pesertaPerformaRendah = Peserta::where('mentor_id', $mentorId)
    ->whereRaw('(
        CASE
            WHEN jenis_target_pencapaian = "SKS" THEN (sks_sekarang / sks_target * 100)
            ELSE (durasi_sekarang / durasi_target * 100)
        END
    ) < 25')
    ->count();
```

**SESUDAH:**

```php
$pesertaPerformaRendah = Peserta::where('mentor_id', $mentorId)
    ->whereRaw('(
        CASE
            WHEN target_method = "sks" THEN
                CASE WHEN target_waktu_tugas > 0 THEN (sks / target_waktu_tugas * 100) ELSE 0 END
            ELSE
                CASE WHEN target_waktu_tugas > 0 THEN (waktu_tugas_tercapai / target_waktu_tugas * 100) ELSE 0 END
        END
    ) < 25')
    ->count();
```

### 2. Update Query: Progress Percentage Calculation

**SEBELUM:**

```php
->map(function($peserta) {
    if ($peserta->jenis_target_pencapaian === 'SKS') {
        $peserta->progress_percentage = $peserta->sks_target > 0
            ? ($peserta->sks_sekarang / $peserta->sks_target * 100)
            : 0;
    } else {
        $peserta->progress_percentage = $peserta->durasi_target > 0
            ? ($peserta->durasi_sekarang / $peserta->durasi_target * 100)
            : 0;
    }
    return $peserta;
})
```

**SESUDAH:**

```php
->map(function($peserta) {
    // target_method = 'sks' atau 'manual'
    if ($peserta->target_method === 'sks') {
        $peserta->progress_percentage = $peserta->target_waktu_tugas > 0
            ? ($peserta->sks / $peserta->target_waktu_tugas * 100)
            : 0;
    } else {
        $peserta->progress_percentage = $peserta->target_waktu_tugas > 0
            ? ($peserta->waktu_tugas_tercapai / $peserta->target_waktu_tugas * 100)
            : 0;
    }
    return $peserta;
})
```

### 3. Update Query: Chart Distribution (Pemula, Menengah, Mahir)

Diperbaiki untuk ketiga kategori dengan menggunakan:

- `target_method` bukan `jenis_target_pencapaian`
- `sks` bukan `sks_sekarang`
- `target_waktu_tugas` bukan `sks_target` atau `durasi_target`
- `waktu_tugas_tercapai` bukan `durasi_sekarang`
- Tambahan pengecekan `CASE WHEN target_waktu_tugas > 0` untuk menghindari division by zero

## Struktur Database yang Benar

### Tabel: `pesertas`

**Kolom yang Relevan untuk Progress Calculation:**

| Kolom                  | Tipe                 | Deskripsi                                |
| ---------------------- | -------------------- | ---------------------------------------- |
| `target_method`        | enum('sks','manual') | Metode perhitungan progress              |
| `sks`                  | integer              | SKS yang sudah tercapai                  |
| `target_waktu_tugas`   | integer              | Target waktu/SKS yang harus dicapai      |
| `waktu_tugas_tercapai` | integer              | Waktu yang sudah tercapai (untuk manual) |
| `waktu_maksimum`       | integer              | Waktu maksimum (nullable)                |

**Logika Perhitungan Progress:**

```
IF target_method = 'sks':
    progress = (sks / target_waktu_tugas) * 100
ELSE:
    progress = (waktu_tugas_tercapai / target_waktu_tugas) * 100
```

## Verifikasi Route

### Route Dashboard

```php
// Dashboard routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/home', [DashboardController::class, 'index'])->name('home');
```

### Method Index di DashboardController

```php
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
```

**Status: ✅ Route sudah benar dan terassign**

## Files yang Dimodifikasi

1. ✅ `app/Http/Controllers/DashboardController.php`
    - Method `mentorDashboard()`
    - Perbaikan semua query yang menggunakan kolom salah

2. ✅ `resources/views/dashboard/mentor.blade.php`
    - Sudah benar, tidak perlu perubahan

3. ✅ `routes/web.php`
    - Sudah benar, tidak perlu perubahan

## Testing Checklist

Setelah perbaikan ini, lakukan testing berikut:

- [ ] Login sebagai user dengan role mentor (role_id = 2)
- [ ] Dashboard mentor muncul tanpa error
- [ ] Kartu statistik menampilkan angka yang benar
- [ ] Tabel "Tugas Menunggu Persetujuan" muncul (bisa kosong jika tidak ada data)
- [ ] Tabel "Progres Peserta Bimbingan" menampilkan peserta dengan progress bar
- [ ] Chart "Distribusi Progress Peserta" ter-render dengan benar
- [ ] Chart "Status Penugasan" ter-render dengan benar
- [ ] Tabel "Log Laporan Harian" muncul (bisa kosong jika tidak ada data)
- [ ] Semua link aksi (Review, Detail) berfungsi
- [ ] Tidak ada error di console browser

## Catatan Penting

### Division by Zero Protection

Semua query sekarang sudah dilindungi dari division by zero dengan pattern:

```sql
CASE WHEN target_waktu_tugas > 0 THEN (nilai / target_waktu_tugas * 100) ELSE 0 END
```

### Nama Kolom yang Sering Salah

❌ `jenis_target_pencapaian` → ✅ `target_method`
❌ `sks_sekarang` → ✅ `sks`
❌ `sks_target` → ✅ `target_waktu_tugas`
❌ `durasi_sekarang` → ✅ `waktu_tugas_tercapai`
❌ `durasi_target` → ✅ `target_waktu_tugas`

### Enum Values

- `target_method`: 'sks' atau 'manual' (lowercase!)
- BUKAN 'SKS' atau 'Manual' (case sensitive!)

## Next Steps

1. Refresh halaman dashboard mentor
2. Verifikasi tidak ada error lagi
3. Test semua fitur dashboard
4. Jika ada data peserta, verifikasi perhitungan progress sudah benar
5. Test dengan berbagai kondisi (ada/tidak ada tugas pending, ada/tidak ada laporan harian)

---

**Status Perbaikan:** ✅ SELESAI
**Ditest pada:** Menunggu user testing
**Last Update:** 20 Oktober 2025 00:00
