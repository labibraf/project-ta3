{{-- resources/views/laporan_harian/create.blade.php --}}
@extends('layouts.mantis')

@section('content')
<div class="card">
    <div>
        @if($selectedPenugasan)
            <a href="{{ route('penugasans.show', $selectedPenugasan->id) }}" class="btn btn-secondary float-start mt-3 mr-2 text-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        @else
            <a href="{{ route('penugasans.index') }}" class="btn btn-secondary float-start mt-3 mr-2 text-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        @endif
    </div>
    <div class="card-header">
        <h2 class="text-left">Tambah Laporan Harian</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan_harian.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="tanggal_laporan">Tanggal Laporan</label>
                <input type="date" name="tanggal_laporan" id="tanggal_laporan"
                    class="form-control @error('tanggal_laporan') is-invalid @enderror"
                    value="{{ old('tanggal_laporan') }}" required autofocus autocomplete="off">
                @error('tanggal_laporan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
            <label for="penugasan_id">Judul Tugas</label>
            @if($selectedPenugasan)
                {{-- Jika ada penugasan yang dipilih dari parameter --}}
                <div class="form-control-plaintext border p-2 bg-light">
                    <strong>{{ $selectedPenugasan->judul_tugas }}</strong>
                    @if($selectedPenugasan->kategori === 'Divisi')
                        <span class="badge bg-info ms-2">Divisi</span>
                    @else
                        <span class="badge bg-primary ms-2">Individu</span>
                    @endif
                </div>
                <input type="hidden" name="penugasan_id" id="penugasan_id" value="{{ $selectedPenugasan->id }}" required>
                <small class="text-muted">Laporan harian untuk tugas ini</small>
            @else
                {{-- Jika tidak ada penugasan yang dipilih, tampilkan dropdown --}}
                <select name="penugasan_id" id="penugasan_id"
                    class="form-control @error('penugasan_id') is-invalid @enderror"
                    required autofocus autocomplete="off">
                    <option value="">Pilih Tugas</option>
                    @foreach($penugasans as $penugasan)
                        <option value="{{ $penugasan->id }}" {{ old('penugasan_id') == $penugasan->id ? 'selected' : '' }}>
                            {{ $penugasan->judul_tugas }}
                            @if($penugasan->kategori === 'Divisi')
                                <span class="badge bg-info ms-2">Divisi</span>
                            @else
                                <span class="badge bg-primary ms-2">Individu</span>
                            @endif
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Tugas divisi bisa dikerjakan oleh semua peserta di bagian yang sama</small>
            @endif
            @error('penugasan_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

            <div class="form-group mb-3">
                <label for="deskripsi_kegiatan">Deskripsi Kegiatan</label>
                <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" cols="30" rows="5"
                          class="form-control @error('deskripsi_kegiatan') is-invalid @enderror"
                          required autofocus autocomplete="off">{{ old('deskripsi_kegiatan') }}</textarea>
                @error('deskripsi_kegiatan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Progres Tugas dengan Nilai Awal -->
            <div class="form-group mb-3" id="progressFormGroup">
                <label for="progres_tugas">Progres Tugas</label>
                <div class="input-group">
                    <input type="number" name="progres_tugas" id="progres_tugas"
                        class="form-control @error('progres_tugas') is-invalid @enderror"
                        value="{{ old('progres_tugas',) }}"
                        required autofocus autocomplete="off" min="0" max="100">
                    <span class="input-group-text">%</span>
                </div>
                @error('progres_tugas')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <!-- Tampilkan progress awal untuk setiap penugasan -->
                <div class="small text-muted mt-2" id="progressInfo">
                    Pilih tugas untuk melihat progress terakhir
                </div>

                <!-- Hidden input untuk menyimpan progres tetap 100 -->
                <input type="hidden" name="progres_final" id="progres_final" value="100">
            </div>

            <div class="form-group mb-3">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" autofocus>
                @error('file')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" id="submitButton">Simpan</button>
        </form>
    </div>
</div>

<!-- Script untuk menampilkan progress terakhir -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const penugasanSelect = document.getElementById('penugasan_id');
    const progressInfo = document.getElementById('progressInfo');
    const progressInput = document.getElementById('progres_tugas');
    const progressFormGroup = document.getElementById('progressFormGroup');
    const progressFinal = document.getElementById('progres_final');
    const submitButton = document.getElementById('submitButton');

    // Data progress dari controller
    const progressData = @json($progressData ?? []);
    const approvalStatus = @json($approvalStatus ?? []);

    function updateProgressInfo(selectedId) {
        if (selectedId && progressData[selectedId] !== undefined) {
            const lastProgress = progressData[selectedId];
            const approval = approvalStatus[selectedId] || {};

            // Jika progres sudah 100% dan sudah di-approve, jangan izinkan laporan baru
            if (lastProgress >= 100 && approval.is_approved) {
                progressFormGroup.style.display = 'none';
                submitButton.style.display = 'none'; // Hide submit button
                progressInfo.innerHTML = `<strong class="text-danger">Tugas sudah di-approve (100%)</strong>`;

                // Buat info box untuk menampilkan status approved
                if (!document.getElementById('taskApprovedInfo')) {
                    const infoBox = document.createElement('div');
                    infoBox.id = 'taskApprovedInfo';
                    infoBox.className = 'alert alert-danger';
                    infoBox.innerHTML = `
                        <i class="fas fa-check-double"></i>
                        <strong>Tugas Telah Di-Approve</strong><br>
                        Tugas ini sudah mencapai 100% dan telah mendapat approval. Tidak bisa menambah laporan harian lagi.
                        <br><a href="{{ route('penugasans.show', $selectedPenugasan->id ?? '') }}" class="btn btn-secondary btn-sm mt-2">
                            <i class="fas fa-arrow-left"></i> Kembali ke Detail Tugas
                        </a>
                    `;
                    progressFormGroup.parentNode.insertBefore(infoBox, progressFormGroup);
                }
                return;
            }            // Jika progres sudah 100% tapi belum di-approve, sembunyikan form progres
            else if (lastProgress >= 100 && !approval.is_approved) {
                progressFormGroup.style.display = 'none';
                progressInfo.innerHTML = `<strong class="text-warning">Tugas selesai, menunggu approval</strong>`;

                // Set nilai progres tetap 100 untuk disimpan
                progressInput.value = 100;
                progressFinal.value = 100;

                // Buat info box terpisah untuk menampilkan status
                if (!document.getElementById('taskCompleteInfo')) {
                    const infoBox = document.createElement('div');
                    infoBox.id = 'taskCompleteInfo';
                    infoBox.className = 'alert alert-warning';
                    infoBox.innerHTML = `
                        <i class="fas fa-clock"></i>
                        <strong>Tugas Selesai - Menunggu Approval</strong><br>
                        Progres tugas ini sudah mencapai 100%. Laporan harian akan disimpan dengan progres 100% hingga mendapat approval.
                    `;
                    progressFormGroup.parentNode.insertBefore(infoBox, progressFormGroup);
                }
            } else {
                // Tampilkan form progres jika belum 100%
                progressFormGroup.style.display = 'block';
                submitButton.style.display = 'block'; // Show submit button

                // Hapus info box jika ada
                const existingInfo = document.getElementById('taskCompleteInfo');
                const existingApproved = document.getElementById('taskApprovedInfo');
                if (existingInfo) existingInfo.remove();
                if (existingApproved) existingApproved.remove();

                progressInfo.innerHTML = `Progress terakhir: <strong>${lastProgress}%</strong>`;

                // Set nilai minimal input sesuai progress terakhir
                progressInput.min = lastProgress;
                progressInput.placeholder = `Minimal ${lastProgress}%`;

                // Jika belum ada input, set default ke lastProgress + 1
                if (!progressInput.value || progressInput.value <= lastProgress) {
                    progressInput.value = Math.min(lastProgress + 1, 100);
                }

                // Set nilai final sesuai input
                progressFinal.value = progressInput.value;
            }
        } else {
            // Jika tidak ada data progres, tampilkan form dengan nilai default
            progressFormGroup.style.display = 'block';
            submitButton.style.display = 'block'; // Show submit button

            // Hapus info box jika ada
            const existingInfo = document.getElementById('taskCompleteInfo');
            const existingApproved = document.getElementById('taskApprovedInfo');
            if (existingInfo) existingInfo.remove();
            if (existingApproved) existingApproved.remove();

            progressInfo.innerHTML = 'Pilih tugas untuk melihat progress terakhir';
            progressInput.min = 0;
            progressInput.placeholder = '';
            progressInput.value = 100; // Default 100% untuk laporan baru
            progressFinal.value = 100;
        }
    }

    // Update nilai final saat input progres berubah
    if (progressInput) {
        progressInput.addEventListener('input', function() {
            progressFinal.value = this.value;
        });
    }

    // Jika ada select dropdown
    if (penugasanSelect && penugasanSelect.tagName === 'SELECT') {
        penugasanSelect.addEventListener('change', function() {
            updateProgressInfo(this.value);
        });

        // Trigger change event jika ada nilai default
        if (penugasanSelect.value) {
            penugasanSelect.dispatchEvent(new Event('change'));
        }
    } else if (penugasanSelect && penugasanSelect.type === 'hidden') {
        // Jika ada hidden input (penugasan sudah dipilih)
        updateProgressInfo(penugasanSelect.value);
    }
});
</script>

@endsection
