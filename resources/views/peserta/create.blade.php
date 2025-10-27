{{-- resources/views/peserta/create.blade.php --}}
@extends('layouts.mantis')

@section('content')
<div class="">
    <div>
        <a href="{{ route('peserta.index') }}" class="btn btn-secondary mb-3">
            < Kembali
        </a>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title">Tambah Peserta Magang</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('peserta.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                value="{{ old('nama_lengkap') }}" required autocomplete="off" autofocus>
                            @error('nama_lengkap')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nomor_identitas">Nomor Identitas (NIK/KTP) *</label>
                            <input type="text" name="nomor_identitas" id="nomor_identitas"
                                class="form-control @error('nomor_identitas') is-invalid @enderror"
                                value="{{ old('nomor_identitas') }}" required autocomplete="off">
                            @error('nomor_identitas')
                                <small class="text-danger">{{ $message }}</small>
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
                                value="{{ old('email') }}" required autocomplete="off">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_telepon">Nomor Telepon *</label>
                            <input type="tel" name="no_telepon" id="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                value="{{ old('no_telepon') }}" required autocomplete="off">
                            @error('no_telepon')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="alamat">Alamat *</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="jenis_kelamin">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <small class="text-danger">{{ $message }}</small>
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
                                value="{{ old('asal_instansi') }}" required autocomplete="off">
                            @error('asal_instansi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jurusan">Jurusan *</label>
                            <input type="text" name="jurusan" id="jurusan"
                                class="form-control @error('jurusan') is-invalid @enderror"
                                value="{{ old('jurusan') }}" required autocomplete="off">
                            @error('jurusan')
                                <small class="text-danger">{{ $message }}</small>
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
                                class="form-control @error('tipe_magang') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe Magang --</option>
                                <option value="Kerja Praktik" {{ old('tipe_magang') == 'Kerja Praktik' ? 'selected' : '' }}>Kerja Praktik</option>
                                <option value="Magang Nasional" {{ old('tipe_magang') == 'Magang Nasional' ? 'selected' : '' }}>Magang Nasional</option>
                                <option value="Penelitian" {{ old('tipe_magang') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                            </select>
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
                                            {{ old('bagian_id') == $bagian->id ? 'selected' : '' }}>
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
                                value="{{ old('sks') }}" min="1" max="30" required>
                            <small class="form-text text-muted">
                                Referensi: KP/Penelitian = 2 SKS, Magang Nasional = 20 SKS
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
                                value="{{ old('tanggal_mulai_magang') }}" required>
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
                                value="{{ old('tanggal_selesai_magang') }}" required>
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
                                            {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>
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
                                <!-- Radio Buttons -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="target_method" id="method_sks" value="sks"
                                        {{ old('target_method', 'sks') == 'sks' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="method_sks">
                                        <strong>Mengikuti SKS</strong> (SKS × 45 jam)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="target_method" id="method_manual" value="manual"
                                        {{ old('target_method') == 'manual' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="method_manual">
                                        <strong>Input Manual</strong> (Tentukan sendiri)
                                    </label>
                                </div>

                                <!-- Input Manual (hidden by default) -->
                                <div id="manual-target-input" class="mb-3" style="display: none;">
                                    <div class="input-group">
                                        <input type="number" name="target_waktu_manual" id="target_waktu_manual"
                                            class="form-control @error('target_waktu_manual') is-invalid @enderror"
                                            value="{{ old('target_waktu_manual') }}" min="1"
                                            placeholder="Masukkan target waktu minimal (jam)" disabled>
                                        <span class="input-group-text">jam</span>
                                    </div>
                                    @error('target_waktu_manual')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Tampilan Perhitungan SKS -->
                                <div id="sks-calculation" class="mt-2">
                                    <small class="text-info d-block">
                                        {{ old('target_method', 'sks') == 'sks' ? 'Otomatis dihitung dari SKS × 45 jam' : '' }}
                                    </small>
                                </div>

                                <!-- Notes Hijau (Target Otomatis) -->
                                <div id="sks-note" class="mt-2 p-2 bg-success bg-opacity-10 border border-success rounded" style="display: {{ old('target_method', 'sks') == 'sks' ? 'block' : 'none' }};">
                                    <small class="text-success">
                                        <strong>Target waktu otomatis:</strong> <span id="sks-total-hours">0</span> jam
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

                {{-- Foto --}}
                <div class="border-bottom mb-4 mt-4">
                    <h4>Foto Peserta</h4>
                </div>

                <div class="form-group mb-3">
                    <label for="foto">Foto Peserta</label>
                    <input type="file" name="foto" id="foto"
                        class="form-control @error('foto') is-invalid @enderror">
                    <small class="form-text text-muted">
                        Format: JPG, PNG, GIF. Maksimal 2MB
                    </small>
                    @error('foto')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
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
            targetWaktuInput.value = '';
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

    // Fungsi untuk menghitung durasi dan waktu maksimum magang
    function calculateMaxTime() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            if (end >= start) {
                let workingDays = 0;
                const currentDate = new Date(start.getTime()); 

                while (currentDate <= end) {
                    const dayOfWeek = currentDate.getDay(); 
                    if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                        workingDays++;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                const maxHours = workingDays * 8; 

                maxTimeInfo.innerHTML = `
                    <strong>Durasi Magang:</strong> ${workingDays} hari kerja<br>
                    <strong>Waktu Maksimum:</strong> ${maxHours} jam <small>(${workingDays} hari × 8 jam/hari)</small>
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
    // Filter mentor berdasarkan bagian
    const bagianSelect = document.getElementById('bagian_id');
    const mentorSelect = document.getElementById('mentor_id');

    if (bagianSelect && mentorSelect) {
        const allMentorOptions = Array.from(mentorSelect.options).slice(1);

        bagianSelect.addEventListener('change', function() {
            const selectedBagianId = this.value;
            mentorSelect.innerHTML = '<option value="">-- Pilih Mentor --</option>';

            if (selectedBagianId) {
                allMentorOptions.forEach(option => {
                    if (option.dataset.bagian === selectedBagianId) {
                        mentorSelect.appendChild(option.cloneNode(true));
                    }
                });
            } else {
                allMentorOptions.forEach(option => mentorSelect.appendChild(option.cloneNode(true)));
            }
        });
    }
    @endif
});
</script>
@endsection
