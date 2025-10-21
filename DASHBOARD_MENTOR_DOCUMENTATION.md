# Dashboard Mentor - Dokumentasi

## Overview

Dashboard Mentor dirancang khusus untuk membantu mentor dalam memantau dan mengelola peserta magang yang menjadi tanggung jawab mereka. Dashboard ini fokus pada informasi yang relevan dan actionable untuk mentor.

## Struktur Dashboard

### PRIORITAS 1: Komponen Penting (Harus Ada)

#### 1. Kartu Statistik Utama (4 Cards)

**Lokasi:** Paling atas dashboard

- **Total Peserta Bimbingan** (Hijau)
    - Menampilkan jumlah total peserta yang dibimbing oleh mentor
    - Query: `Peserta::where('mentor_id', $mentorId)->count()`

- **Tugas Perlu Review** (Kuning/Warning)
    - Menampilkan jumlah tugas yang sudah selesai dikerjakan peserta tapi belum disetujui
    - Query: Tugas dengan `status_tugas = 'Selesai'` dan `is_approved = 0`
    - **Action Required:** Mentor harus mereview dan menyetujui

- **Performa Rendah** (Merah)
    - Menampilkan jumlah peserta dengan progress < 25%
    - Query: Berdasarkan perhitungan SKS atau durasi
    - **Action Required:** Mentor perlu memberikan perhatian khusus

- **Tugas Terlambat** (Merah)
    - Menampilkan jumlah tugas yang melewati deadline tapi belum selesai
    - Query: `deadline_tugas < now()` dan `status_tugas != 'Selesai'`
    - **Action Required:** Mentor perlu mengingatkan peserta

#### 2. Tabel: Tugas Menunggu Persetujuan

**Lokasi:** Setelah kartu statistik

**Kolom:**

- Nama Peserta (dengan asal instansi)
- Judul Tugas (dengan deskripsi singkat)
- Tanggal Pengumpulan
- Status (Badge warning)
- Aksi (Tombol Review)

**Fitur:**

- Limit 10 tugas terakhir
- Sortir berdasarkan tanggal update (terbaru)
- Direct link ke halaman detail tugas untuk review
- Empty state ketika tidak ada tugas

**Tujuan:** Ini adalah "inbox" utama mentor - tugas yang memerlukan action segera

#### 3. Tabel: Progres Peserta Bimbingan

**Lokasi:** Setelah tabel pending approvals

**Kolom:**

- Nama Peserta (dengan jenis magang)
- Asal Instansi
- Progress Bar (dengan persentase)
- Status (Badge: Pemula/Menengah/Mahir)
- Aksi (Tombol Detail)

**Fitur:**

- Progress bar dengan warna dinamis:
    - Merah: < 25% (Pemula)
    - Kuning: 25-75% (Menengah)
    - Hijau: > 75% (Mahir)
- Sortir berdasarkan progress (tertinggi ke terendah)
- Perhitungan progress otomatis dari SKS atau durasi

**Tujuan:** Memberikan overview cepat tentang kemajuan setiap peserta

---

### PRIORITAS 2: Komponen Bagus untuk Dimiliki (Kontekstual)

#### 4. Chart: Distribusi Progress Peserta (Donut Chart)

**Lokasi:** Baris pertama chart section

**Data:**

- Pemula (<25%): Warna Merah
- Menengah (25-75%): Warna Kuning
- Mahir (>75%): Warna Hijau

**Fitur:**

- Total peserta ditampilkan di tengah donut
- Persentase untuk setiap kategori
- Tooltip dengan jumlah peserta

**Tujuan:** Visualisasi cepat distribusi kemampuan peserta bimbingan

#### 5. Chart: Status Penugasan (Pie Chart)

**Lokasi:** Baris pertama chart section (sebelah kanan)

**Data:**

- Selesai: Warna Hijau
- Dikerjakan: Warna Kuning
- Belum Dimulai: Warna Merah

**Fitur:**

- Persentase untuk setiap status
- Tooltip dengan jumlah tugas
- Data hanya dari peserta bimbingan mentor

**Tujuan:** Melihat tingkat penyelesaian tugas secara keseluruhan

#### 6. Tabel: Log Laporan Harian Terbaru

**Lokasi:** Paling bawah dashboard

**Kolom:**

- Nama Peserta
- Aktivitas/Tugas (truncated 60 karakter)
- Tanggal Laporan
- Aksi (Tombol View)

**Fitur:**

- Limit 10 laporan terakhir
- Sortir berdasarkan tanggal (terbaru)
- Empty state ketika belum ada laporan
- Link "Lihat Semua" di header

**Tujuan:** Monitoring aktivitas harian peserta tanpa harus membuka profil satu per satu

---

## Data Source & Query Logic

### Progress Calculation

```php
if ($peserta->jenis_target_pencapaian === 'SKS') {
    $progress = ($peserta->sks_sekarang / $peserta->sks_target) * 100;
} else {
    $progress = ($peserta->durasi_sekarang / $peserta->durasi_target) * 100;
}
```

### Filter by Mentor

Semua query difilter berdasarkan `mentor_id` untuk memastikan mentor hanya melihat data peserta bimbingannya:

```php
whereHas('peserta', function($q) use ($mentorId) {
    $q->where('mentor_id', $mentorId);
})
```

---

## File Locations

### View

- **File:** `resources/views/dashboard/mentor.blade.php`
- **Layout:** Extends `layouts.mantis`
- **Charts:** ApexCharts library

### Controller

- **File:** `app/Http/Controllers/DashboardController.php`
- **Method:** `mentorDashboard()`
- **Middleware:** `auth`

### Routes

```php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Automatically redirects to mentorDashboard() if user role is mentor
```

---

## Styling & Assets

### Custom CSS

- Avatar styles (`.avtar`, `.avtar-s`)
- Light background colors (`.bg-light-primary`, etc.)
- Progress bar styling
- Card shadows and borders

### JavaScript

- ApexCharts for data visualization
- Responsive chart rendering
- DOM ready event handling

### Icons

- Tabler Icons (ti ti-\*)
- Digunakan untuk:
    - ti-users: Peserta
    - ti-clipboard-check: Tugas Review
    - ti-alert-triangle: Warning
    - ti-clock-exclamation: Terlambat
    - ti-eye: View/Detail

---

## Security & Access Control

### Authentication

- Middleware `auth` diperlukan
- Check `Auth::user()->mentor` untuk memastikan user memiliki data mentor
- Redirect ke home dengan error message jika mentor tidak ditemukan

### Data Isolation

- Semua data difilter berdasarkan `mentor_id`
- Mentor **TIDAK BISA** melihat data peserta mentor lain
- Query menggunakan `whereHas` untuk relasi peserta

---

## Empty States

Setiap tabel memiliki empty state yang user-friendly:

- Icon yang sesuai dengan konten
- Pesan yang jelas
- Styling yang konsisten

**Contoh:**

```blade
@forelse($data as $item)
    <!-- Display data -->
@empty
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="ti ti-icon fs-1 text-muted d-block mb-2"></i>
            <p class="text-muted mb-0">Pesan empty state</p>
        </td>
    </tr>
@endforelse
```

---

## Future Enhancements (Optional)

Jika diperlukan di masa depan, bisa ditambahkan:

1. Filter berdasarkan tanggal/periode
2. Export data ke Excel/PDF
3. Notifikasi real-time untuk tugas baru
4. Grafik tren progress peserta dari waktu ke waktu
5. Comparison antara peserta
6. Quick actions (approve multiple tasks)

---

## Perbedaan dengan Dashboard Admin

| Aspek       | Admin Dashboard    | Mentor Dashboard           |
| ----------- | ------------------ | -------------------------- |
| **Scope**   | Seluruh sistem     | Hanya peserta bimbingannya |
| **Charts**  | 15+ charts         | 2 charts (fokus)           |
| **Tables**  | Global data        | Filtered by mentor_id      |
| **Actions** | CRUD all data      | Review & monitor only      |
| **Focus**   | Strategic overview | Operational & actionable   |

---

## Maintenance Notes

### Performa Query

- Gunakan `with()` untuk eager loading relasi
- Index pada kolom `mentor_id` di tabel peserta
- Limit hasil query untuk tabel (10 rows)
- Cache chart data jika diperlukan (belum implemented)

### Testing Checklist

- [ ] Dashboard tampil untuk user dengan role mentor
- [ ] Data hanya menampilkan peserta bimbingan mentor yang login
- [ ] Empty state muncul ketika tidak ada data
- [ ] Link aksi (Review, Detail) berfungsi dengan benar
- [ ] Charts ter-render dengan data yang benar
- [ ] Responsive di mobile/tablet
- [ ] Error handling ketika mentor_id tidak ada

---

**Dibuat:** 19 Oktober 2025  
**Last Update:** 19 Oktober 2025  
**Version:** 1.0.0
