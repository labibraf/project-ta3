# Perbaikan Dashboard Mentor - Update

## Tanggal: 20 Oktober 2025 (Update Ke-2)

## Ringkasan Error yang Ditemukan dan Diperbaiki

### ✅ Error 1: Column `jenis_target_pencapaian` tidak ditemukan

**File:** `DashboardController.php`
**Fix:** Ganti dengan `target_method`

### ✅ Error 2: Column `deadline_tugas` tidak ditemukan

**File:** `DashboardController.php`
**Fix:** Ganti dengan `deadline`

### ✅ Error 3: Column `nama_tugas` tidak ditemukan

**File:** `mentor.blade.php`
**Fix:** Ganti dengan `judul_tugas`

### ✅ Error 4: Column `jenis_magang` tidak ditemukan

**File:** `mentor.blade.php`
**Fix:** Ganti dengan `tipe_magang`

### ✅ Error 5: Enum value salah untuk `status_tugas`

**File:** `DashboardController.php`
**Fix:** Ganti `'Belum Dimulai'` dengan `'Belum'`

---

## Detail Perbaikan

### 1. Perbaikan Kolom `deadline_tugas` → `deadline`

**Lokasi:** `app/Http/Controllers/DashboardController.php` line ~490

**SEBELUM:**

```php
$tugasTerlambat = Penugasan::whereHas('peserta', function($q) use ($mentorId) {
    $q->where('mentor_id', $mentorId);
})
->where('deadline_tugas', '<', now())
->where('status_tugas', '!=', 'Selesai')
->count();
```

**SESUDAH:**

```php
$tugasTerlambat = Penugasan::whereHas('peserta', function($q) use ($mentorId) {
    $q->where('mentor_id', $mentorId);
})
->where('deadline', '<', now())
->where('status_tugas', '!=', 'Selesai')
->count();
```

**Alasan:**
Tabel `penugasans` memiliki kolom `deadline` (date), bukan `deadline_tugas`

---

### 2. Perbaikan Kolom `nama_tugas` → `judul_tugas`

**Lokasi:** `resources/views/dashboard/mentor.blade.php` line ~84

**SEBELUM:**

```blade
<h6 class="mb-0">{{ $tugas->nama_tugas }}</h6>
<small class="text-muted">{{ Str::limit($tugas->deskripsi_tugas, 50) }}</small>
```

**SESUDAH:**

```blade
<h6 class="mb-0">{{ $tugas->judul_tugas }}</h6>
<small class="text-muted">{{ Str::limit($tugas->deskripsi_tugas, 50) }}</small>
```

**Alasan:**
Tabel `penugasans` memiliki kolom `judul_tugas`, bukan `nama_tugas`

---

### 3. Perbaikan Kolom `jenis_magang` → `tipe_magang`

**Lokasi:** `resources/views/dashboard/mentor.blade.php` line ~149

**SEBELUM:**

```blade
<h6 class="mb-0">{{ $peserta->nama_lengkap }}</h6>
<small class="text-muted">{{ $peserta->jenis_magang }}</small>
```

**SESUDAH:**

```blade
<h6 class="mb-0">{{ $peserta->nama_lengkap }}</h6>
<small class="text-muted">{{ $peserta->tipe_magang }}</small>
```

**Alasan:**
Tabel `pesertas` memiliki kolom `tipe_magang`, bukan `jenis_magang`

---

### 4. Perbaikan Enum Value `status_tugas`

**Lokasi:** `app/Http/Controllers/DashboardController.php` line ~570

**SEBELUM:**

```php
$tugasBelumDimulai = Penugasan::whereHas('peserta', function($q) use ($mentorId) {
    $q->where('mentor_id', $mentorId);
})->where('status_tugas', 'Belum Dimulai')->count();
```

**SESUDAH:**

```php
$tugasBelumDimulai = Penugasan::whereHas('peserta', function($q) use ($mentorId) {
    $q->where('mentor_id', $mentorId);
})->where('status_tugas', 'Belum')->count();
```

**Alasan:**
Enum values di tabel `penugasans` adalah: `['Belum', 'Dikerjakan', 'Selesai']`, bukan `'Belum Dimulai'`

---

## Struktur Database yang Benar

### Tabel: `penugasans`

| Kolom             | Tipe    | Enum Values                      |
| ----------------- | ------- | -------------------------------- |
| `judul_tugas`     | string  | -                                |
| `deskripsi_tugas` | text    | -                                |
| `deadline`        | date    | -                                |
| `status_tugas`    | enum    | 'Belum', 'Dikerjakan', 'Selesai' |
| `kategori`        | enum    | 'Individu', 'Divisi'             |
| `is_approved`     | boolean | 0 atau 1                         |

### Tabel: `pesertas`

| Kolom                  | Tipe    | Enum Values                                      |
| ---------------------- | ------- | ------------------------------------------------ |
| `nama_lengkap`         | string  | -                                                |
| `tipe_magang`          | enum    | 'Kerja Praktik', 'Magang Nasional', 'Penelitian' |
| `target_method`        | enum    | 'sks', 'manual'                                  |
| `sks`                  | integer | -                                                |
| `target_waktu_tugas`   | integer | -                                                |
| `waktu_tugas_tercapai` | integer | -                                                |

---

## Mapping Nama Kolom - Cheat Sheet

### ❌ SALAH → ✅ BENAR

**Tabel Pesertas:**

- ❌ `jenis_target_pencapaian` → ✅ `target_method`
- ❌ `sks_sekarang` → ✅ `sks`
- ❌ `sks_target` → ✅ `target_waktu_tugas`
- ❌ `durasi_sekarang` → ✅ `waktu_tugas_tercapai`
- ❌ `durasi_target` → ✅ `target_waktu_tugas`
- ❌ `jenis_magang` → ✅ `tipe_magang`

**Tabel Penugasans:**

- ❌ `deadline_tugas` → ✅ `deadline`
- ❌ `nama_tugas` → ✅ `judul_tugas`

**Enum Values:**

- ❌ `'Belum Dimulai'` → ✅ `'Belum'`
- ❌ `'SKS'` (uppercase) → ✅ `'sks'` (lowercase)
- ❌ `'Manual'` → ✅ `'manual'` (lowercase)

---

## Files yang Dimodifikasi (Update Ke-2)

1. ✅ `app/Http/Controllers/DashboardController.php`
    - Line ~490: `deadline_tugas` → `deadline`
    - Line ~570: `'Belum Dimulai'` → `'Belum'`

2. ✅ `resources/views/dashboard/mentor.blade.php`
    - Line ~84: `nama_tugas` → `judul_tugas`
    - Line ~149: `jenis_magang` → `tipe_magang`

---

## Testing Checklist - Update

Setelah perbaikan kedua ini, test kembali:

- [ ] Login sebagai mentor
- [ ] Dashboard muncul tanpa error SQL
- [ ] Kartu "Tugas Terlambat" menampilkan angka yang benar
- [ ] Tabel "Tugas Menunggu Persetujuan" menampilkan judul tugas dengan benar
- [ ] Tabel "Progres Peserta" menampilkan tipe magang dengan benar
- [ ] Chart "Status Penugasan" menghitung status 'Belum' dengan benar
- [ ] Tidak ada error di console browser
- [ ] Tidak ada error di Laravel log

---

## Query Validation

Untuk memastikan tidak ada error lagi, jalankan query ini di database:

```sql
-- Test query untuk Tugas Terlambat
SELECT COUNT(*) FROM penugasans
WHERE EXISTS (
    SELECT * FROM pesertas
    WHERE penugasans.peserta_id = pesertas.id
    AND mentor_id = 7
)
AND deadline < NOW()
AND status_tugas != 'Selesai';

-- Test query untuk Status Tugas
SELECT status_tugas, COUNT(*)
FROM penugasans
WHERE EXISTS (
    SELECT * FROM pesertas
    WHERE penugasans.peserta_id = pesertas.id
    AND mentor_id = 7
)
GROUP BY status_tugas;

-- Test query untuk Progress Peserta
SELECT
    nama_lengkap,
    tipe_magang,
    target_method,
    CASE
        WHEN target_method = 'sks' THEN
            CASE WHEN target_waktu_tugas > 0 THEN (sks / target_waktu_tugas * 100) ELSE 0 END
        ELSE
            CASE WHEN target_waktu_tugas > 0 THEN (waktu_tugas_tercapai / target_waktu_tugas * 100) ELSE 0 END
    END AS progress_percentage
FROM pesertas
WHERE mentor_id = 7;
```

**Note:** Ganti `mentor_id = 7` dengan ID mentor yang sedang login

---

## Status Perbaikan

| Error                     | Status   | File                    | Line     |
| ------------------------- | -------- | ----------------------- | -------- |
| `jenis_target_pencapaian` | ✅ Fixed | DashboardController.php | Multiple |
| `deadline_tugas`          | ✅ Fixed | DashboardController.php | ~490     |
| `nama_tugas`              | ✅ Fixed | mentor.blade.php        | ~84      |
| `jenis_magang`            | ✅ Fixed | mentor.blade.php        | ~149     |
| `'Belum Dimulai'`         | ✅ Fixed | DashboardController.php | ~570     |

---

**Status:** ✅ SEMUA ERROR DIPERBAIKI
**Ditest pada:** Menunggu user testing
**Last Update:** 20 Oktober 2025 - Update Ke-2
