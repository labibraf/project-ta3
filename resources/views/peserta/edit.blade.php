{{-- resources/views/peserta/edit.blade.php --}}

@extends('layouts.mantis')

@section('content')
<div class="">
    <div>
        <a href="{{ route('peserta.index') }}" class="btn btn-secondary mb-3">
            < Kembali </a>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title">Edit Peserta Magang</h2>
        </div>
        <div class="card-body">
            @if(!$peserta->can_edit_data_akademis)
                <div class="alert alert-warning">
                    <i class="fas fa-lock"></i>
                    <strong>Perhatian!</strong> Data akademis peserta ini tidak dapat diubah karena laporan akhir sudah diterima.
                    Jika ingin mengubah data akademis (SKS, tanggal magang, target waktu), batalkan terlebih dahulu laporan akhir yang sudah diterima.
                    <br><small class="text-muted">Data yang tidak dapat diubah: Jumlah SKS, Tanggal Mulai & Selesai Magang, Metode Target Waktu, Tipe Magang</small>
                </div>
            @endif

            <form action="{{ route('peserta.update', $peserta->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Informasi Pribadi --}}
                <div class="border-bottom mb-4">
                    <h4>Informasi Pribadi</h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_lengkap">Nama Lengkap Peserta *</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}" required autocomplete="off" autofocus>
                            @error('nama_lengkap')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nomor_identitas">Nomor Identitas (NIK/KTP) *</label>
                            <input type="text" name="nomor_identitas" id="nomor_identitas"
                                class="form-control @error('nomor_identitas') is-invalid @enderror"
                                value="{{ old('nomor_identitas', $peserta->nomor_identitas) }}" required autocomplete="off" readonly>
                            @error('nomor_identitas')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $peserta->email) }}" required autocomplete="off">
                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_telepon">Nomor Telepon *</label>
                            <input type="tel" name="no_telepon" id="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                value="{{ old('no_telepon', $peserta->no_telepon) }}" required autocomplete="off">
                            @error('no_telepon')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="alamat">Alamat *</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        required>{{ old('alamat', $peserta->alamat) }}</textarea>
                    @error('alamat')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="jenis_kelamin">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki"
                            {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- Data Akademik --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Data Akademik</h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="asal_instansi">Asal Instansi *</label>
                            <input type="text" name="asal_instansi" id="asal_instansi"
                                class="form-control @error('asal_instansi') is-invalid @enderror"
                                value="{{ old('asal_instansi', $peserta->asal_instansi) }}" required autocomplete="off">
                            @error('asal_instansi')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jurusan">Jurusan *</label>
                            <input type="text" name="jurusan" id="jurusan"
                                class="form-control @error('jurusan') is-invalid @enderror"
                                value="{{ old('jurusan', $peserta->jurusan) }}" required autocomplete="off">
                            @error('jurusan')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Magang --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Informasi Magang</h4>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="tipe_magang">Tipe Magang *</label>
                            <select name="tipe_magang" id="tipe_magang"
                                class="form-control @error('tipe_magang') is-invalid @enderror" required
                                {{ !$peserta->can_edit_data_akademis ? 'disabled' : '' }}>
                                <option value="">-- Pilih Tipe Magang --</option>
                                <option value="Kerja Praktik" {{ old('tipe_magang', $peserta->tipe_magang) == 'Kerja Praktik' ? 'selected' : '' }}>Kerja Praktik</option>
                                <option value="Magang Nasional" {{ old('tipe_magang', $peserta->tipe_magang) == 'Magang Nasional' ? 'selected' : '' }}>Magang Nasional</option>
                                <option value="Penelitian" {{ old('tipe_magang', $peserta->tipe_magang) == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                            </select>
                            @if(!$peserta->can_edit_data_akademis)
                                <input type="hidden" name="tipe_magang" value="{{ $peserta->tipe_magang }}">
                                <small class="form-text text-warning"><i class="fas fa-lock"></i> Field ini tidak dapat diubah</small>
                            @endif
                            @error('tipe_magang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    @if($isAdmin)
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="bagian_id">Bagian *</label>
                                <select name="bagian_id" id="bagian_id"
                                    class="form-control @error('bagian_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Bagian --</option>
                                    @foreach ($bagians as $bagian)
                                        <option value="{{ $bagian->id }}"
                                            {{ old('bagian_id', $peserta->bagian_id) == $bagian->id ? 'selected' : '' }}>
                                            {{ $bagian->nama_bagian }}</option>
                                    @endforeach
                                </select>
                                @error('bagian_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @else
                        @if(Auth::check() && Auth::user()->mentor)
                            <input type="hidden" name="bagian_id" value="{{ Auth::user()->mentor->bagian_id }}">
                        @endif
                    @endif

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="sks">Jumlah SKS *</label>
                            <input type="number" name="sks" id="sks"
                                class="form-control @error('sks') is-invalid @enderror"
                                value="{{ old('sks', $peserta->sks) }}" min="1" max="30" required
                                {{ !$peserta->can_edit_data_akademis ? 'readonly' : '' }}>
                            <small class="form-text text-muted">
                                Referensi: KP/Penelitian = 2 SKS, Magang Nasional = 20 SKS
                                @if(!$peserta->can_edit_data_akademis)
                                    <br><span class="text-warning"><i class="fas fa-lock"></i> Field ini tidak dapat diubah</span>
                                @endif
                            </small>
                            @error('sks')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="tanggal_mulai_magang">Waktu Mulai Magang *</label>
                            <input type="date" name="tanggal_mulai_magang" id="tanggal_mulai_magang"
                                class="form-control @error('tanggal_mulai_magang') is-invalid @enderror"
                                value="{{ old('tanggal_mulai_magang', $peserta->tanggal_mulai_magang) }}" required
                                {{ !$peserta->can_edit_data_akademis ? 'readonly' : '' }}>
                            @if(!$peserta->can_edit_data_akademis)
                                <small class="form-text text-warning"><i class="fas fa-lock"></i> Field ini tidak dapat diubah</small>
                            @endif
                            @error('tanggal_mulai_magang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="tanggal_selesai_magang">Waktu Selesai Magang *</label>
                            <input type="date" name="tanggal_selesai_magang" id="tanggal_selesai_magang"
                                class="form-control @error('tanggal_selesai_magang') is-invalid @enderror"
                                value="{{ old('tanggal_selesai_magang', $peserta->tanggal_selesai_magang) }}" required
                                {{ !$peserta->can_edit_data_akademis ? 'readonly' : '' }}>
                            @if(!$peserta->can_edit_data_akademis)
                                <small class="form-text text-warning"><i class="fas fa-lock"></i> Field ini tidak dapat diubah</small>
                            @endif
                            @error('tanggal_selesai_magang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    @if($isAdmin)
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="mentor_id">Mentor Penanggung Jawab *</label>
                                <select name="mentor_id" id="mentor_id"
                                    class="form-control @error('mentor_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Mentor --</option>
                                    @foreach ($mentors as $mentor)
                                        <option value="{{ $mentor->id }}" data-bagian="{{ $mentor->bagian_id }}"
                                            {{ old('mentor_id', $peserta->mentor_id) == $mentor->id ? 'selected' : '' }}>
                                            {{ $mentor->nama_mentor ?? $mentor->nama_lengkap ?? 'Nama Tidak Dikenal' }}</option>
                                    @endforeach
                                </select>
                                @error('mentor_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @else
                        @if(Auth::check() && Auth::user()->mentor)
                            <input type="hidden" name="mentor_id" value="{{ Auth::user()->mentor->id }}">
                        @endif
                    @endif
                </div>

                {{-- Target Waktu Minimal dan Estimasi Waktu Maksimum --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Target & Estimasi Waktu</h4>
                </div>

                <div class="row">
                    <!-- Kolom Kiri: Target Waktu Minimal -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Target Waktu Minimal *</h6>
                            </div>
                            <div class="card-body">
                                @if(!$peserta->can_edit_data_akademis)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-lock"></i> <strong>Target waktu tidak dapat diubah</strong><br>
                                        <small>Metode target waktu sudah terkunci karena laporan akhir telah diterima.</small>
                                    </div>
                                @endif

                                <!-- Radio Buttons -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="target_method" id="method_sks" value="sks"
                                        {{ old('target_method', $peserta->target_method ?? 'sks') == 'sks' ? 'checked' : '' }} required
                                        {{ !$peserta->can_edit_data_akademis ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="method_sks">
                                        <strong>Mengikuti SKS</strong> (SKS × 45 jam)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="target_method" id="method_manual" value="manual"
                                        {{ old('target_method', $peserta->target_method ?? 'sks') == 'manual' ? 'checked' : '' }}
                                        {{ !$peserta->can_edit_data_akademis ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="method_manual">
                                        <strong>Input Manual</strong> (Tentukan sendiri)
                                    </label>
                                </div>

                                @if(!$peserta->can_edit_data_akademis)
                                    <input type="hidden" name="target_method" value="{{ $peserta->target_method ?? 'sks' }}">
                                @endif

                                <!-- Input Manual (hidden by default) -->
                                <div id="manual-target-input" class="mb-3" style="display: {{ old('target_method', $peserta->target_method ?? 'sks') == 'manual' ? 'block' : 'none' }};">
                                    <div class="input-group">
                                        <input type="number" name="target_waktu_manual" id="target_waktu_manual"
                                            class="form-control @error('target_waktu_manual') is-invalid @enderror"
                                            value="{{ old('target_waktu_manual', ($peserta->target_method ?? 'sks') == 'manual' ? $peserta->target_waktu_tugas : '') }}" min="1"
                                            placeholder="Masukkan target waktu minimal (jam)"
                                            {{ old('target_method', $peserta->target_method ?? 'sks') == 'manual' ? '' : 'disabled' }}
                                            {{ !$peserta->can_edit_data_akademis ? 'readonly' : '' }}>
                                        <span class="input-group-text">jam</span>
                                    </div>
                                    @error('target_waktu_manual')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Tampilan Perhitungan SKS -->
                                <div id="sks-calculation" class="mt-2">
                                    <small class="text-info d-block">
                                        {{ old('target_method', $peserta->target_method ?? 'sks') == 'sks' ? 'Otomatis dihitung dari SKS × 45 jam' : '' }}
                                    </small>
                                </div>

                                <!-- Notes Hijau (Target Otomatis) -->
                                <div id="sks-note" class="mt-2 p-2 bg-success bg-opacity-10 border border-success rounded" style="display: {{ old('target_method', $peserta->target_method ?? 'sks') == 'sks' ? 'block' : 'none' }};">
                                    <small class="text-success">
                                        <strong>Target waktu otomatis:</strong> <span id="sks-total-hours">{{ ($peserta->sks ?? 0) * 45 }}</span> jam
                                    </small>
                                </div>

                                @error('target_method')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Estimasi Waktu Maksimum -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Estimasi Waktu Maksimum</h6>
                            </div>
                            <div class="card-body">
                                <div id="max-time-info" class="p-3 bg-light border rounded">
                                    <small class="text-muted">
                                        Pilih tanggal mulai dan selesai untuk melihat estimasi waktu maksimum
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}

                {{-- Foto --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Foto Peserta</h4>
                </div>

                 <div class="form-group mb-3">
                    <label for="foto">Foto Peserta</label>
                    <input type="file" name="foto" id="foto"
                        class="form-control @error('foto') is-invalid @enderror">
                    <small class="form-text text-muted">
                        Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.
                    </small>
                    @error('foto')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                @if ($peserta->foto)
                    <div class="form-group mb-3">
                        <label>Foto Saat Ini:</label><br>
                        <img src="{{ asset('storage/foto_peserta/' . $peserta->foto) }}"
                             alt="Foto {{ $peserta->nama_lengkap }}"
                             class="img-thumbnail"
                             style="max-height: 150px;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display:none;" class="alert alert-warning">
                            <strong>Gambar tidak dapat dimuat!</strong><br>
                            Silakan upload ulang foto peserta.
                        </div>
                    </div>
                @endif


                {{-- Tombol --}}
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    <a href="{{ route('peserta.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodSks = document.getElementById('method_sks');
    const methodManual = document.getElementById('method_manual');
    const targetWaktuInput = document.getElementById('target_waktu_manual');
    const sksInput = document.getElementById('sks');
    const sksCalculation = document.getElementById('sks-calculation');
    const sksNote = document.getElementById('sks-note');
    const sksTotalHours = document.getElementById('sks-total-hours');
    const startDateInput = document.getElementById('tanggal_mulai_magang');
    const endDateInput = document.getElementById('tanggal_selesai_magang');
    const maxTimeInfo = document.getElementById('max-time-info');
    const manualInputDiv = document.getElementById('manual-target-input');

    // Fungsi untuk menangani perubahan metode target
    function handleTargetMethodChange() {
        if (methodSks.checked) {
            targetWaktuInput.disabled = true;
            targetWaktuInput.required = false;
            manualInputDiv.style.display = 'none';
            calculateSksHours(); // Tampilkan perhitungan
        } else if (methodManual.checked) {
            targetWaktuInput.disabled = false;
            targetWaktuInput.required = true;
            manualInputDiv.style.display = 'block';
            sksCalculation.innerHTML = '<small class="text-muted">Target waktu akan diambil dari input manual</small>';
            sksNote.style.display = 'none';
        }
    }

    // Fungsi untuk menghitung jam berdasarkan SKS
    function calculateSksHours() {
        if (methodSks.checked) {
            const sksValue = parseInt(sksInput.value) || 0;
            if (sksValue > 0) {
                const totalHours = sksValue * 45;
                sksTotalHours.textContent = totalHours;
                sksCalculation.innerHTML = `<small class="text-success"><strong>Target waktu otomatis: ${totalHours} jam</strong> (${sksValue} SKS × 45 jam)</small>`;
                sksNote.style.display = 'block';
            } else {
                sksCalculation.innerHTML = '<small class="text-muted">Masukkan jumlah SKS untuk melihat perhitungan</small>';
                sksNote.style.display = 'none';
            }
        }
    }

    // Function to calculate maximum time
    function calculateMaxTime() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            if (end >= start) {
                const timeDiff = end.getTime() - start.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include end date
                const maxHours = daysDiff * 8; // 8 hours per day

                maxTimeInfo.innerHTML = `
                    <strong>Durasi Magang:</strong> ${daysDiff} hari<br>
                    <strong>Waktu Maksimum:</strong> ${maxHours} jam <small>(${daysDiff} hari × 8 jam/hari)</small>
                `;
            } else {
                maxTimeInfo.innerHTML = '<small class="text-danger">Tanggal selesai harus setelah tanggal mulai</small>';
            }
        } else {
            maxTimeInfo.innerHTML = '<small class="text-muted">Pilih tanggal mulai dan selesai untuk melihat estimasi waktu maksimum</small>';
        }
    }

    // Event listeners
    methodSks.addEventListener('change', handleTargetMethodChange);
    methodManual.addEventListener('change', handleTargetMethodChange);
    sksInput.addEventListener('input', calculateSksHours);
    startDateInput.addEventListener('change', calculateMaxTime);
    endDateInput.addEventListener('change', calculateMaxTime);

    // Inisialisasi saat halaman dimuat
    handleTargetMethodChange();
    calculateMaxTime();

    @if($isAdmin)
    // Filter mentor berdasarkan bagian yang dipilih
    const bagianSelect = document.getElementById('bagian_id');
    const mentorSelect = document.getElementById('mentor_id');

    if (bagianSelect && mentorSelect) {
        // Simpan semua opsi mentor saat halaman pertama kali dimuat
        const allMentorOptions = Array.from(mentorSelect.options).slice(1); // Skip option pertama

        // Simpan mentor yang sudah dipilih sebelumnya
        const currentMentorId = '{{ $peserta->mentor_id ?? "" }}';
        const currentBagianId = '{{ $peserta->bagian_id ?? "" }}';

        // Fungsi untuk filter mentor berdasarkan bagian
        function filterMentorsByBagian(selectedBagianId) {
            // Clear mentor options except the first one
            mentorSelect.innerHTML = '<option value="">-- Pilih Mentor --</option>';

            if (selectedBagianId) {
                // Filter dan tambahkan mentor yang sesuai dengan bagian yang dipilih
                allMentorOptions.forEach(option => {
                    if (option.dataset.bagian === selectedBagianId) {
                        const newOption = option.cloneNode(true);
                        // Jaga seleksi mentor yang sudah ada sebelumnya
                        if (newOption.value === currentMentorId) {
                            newOption.selected = true;
                        }
                        mentorSelect.appendChild(newOption);
                    }
                });
            } else {
                // Jika tidak ada bagian dipilih, tampilkan semua mentor
                allMentorOptions.forEach(option => {
                    const newOption = option.cloneNode(true);
                    // Jaga seleksi mentor yang sudah ada sebelumnya
                    if (newOption.value === currentMentorId) {
                        newOption.selected = true;
                    }
                    mentorSelect.appendChild(newOption);
                });
            }
        }

        // Inisialisasi filter berdasarkan bagian yang sudah dipilih
        if (currentBagianId) {
            filterMentorsByBagian(currentBagianId);
        }

        // Event listener untuk perubahan bagian
        bagianSelect.addEventListener('change', function() {
            const selectedBagianId = this.value;
            filterMentorsByBagian(selectedBagianId);
        });
    }
    @endif
});
</script>

<style>
/* Styling untuk field yang tidak dapat diedit */
input[readonly], select[disabled] {
    background-color: #f8f9fa !important;
    opacity: 0.7;
}

.form-check-input[disabled] + .form-check-label {
    opacity: 0.7;
    color: #6c757d;
}

.text-warning {
    color: #856404 !important;
}

.alert-warning {
    border-left: 4px solid #ffc107;
}
</style>

@endsection
