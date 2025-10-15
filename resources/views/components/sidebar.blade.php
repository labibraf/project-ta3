<div>
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('home') }}" class="b-brand text-primary">
                    <!-- Ganti dengan path logo Anda jika perlu -->
                    <img src="{{ asset('template/dist/assets/images/2.png') }}" alt="logo" class="img-fluid w-80 h-auto">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <x-sidebar.links title="Dashboard" route="dashboard" icon="ti ti-dashboard"></x-sidebar.links>

                    @if (Auth::check())
                        @if (Auth::user()->role_id == 1)
                            {{-- Menu untuk Admin --}}
                            <x-sidebar.links title="Data Peserta" route="peserta.index" icon="ti ti-plant"></x-sidebar.links>
                            <x-sidebar.links title="Data Mentor" route="mentor.index" icon="ti ti-user"></x-sidebar.links>
                            <x-sidebar.links title="Data Bagian" route="bagian.index" icon="ti ti-vector-triangle"></x-sidebar.links>
                            <x-sidebar.links title="Data User" route="users.index" icon="ti ti-users"></x-sidebar.links>
                            <x-sidebar.links title="Daftar Tugas" route="penugasans.index" icon="ti ti-file-text"></x-sidebar.links>
                            <x-sidebar.links title="Laporan Akhir" route="laporan-akhir.index" icon="ti ti-file-text"></x-sidebar.links>
                            @elseif (Auth::user()->isMentor())
                            {{-- Menu untuk Mentor --}}
                            <x-sidebar.links title="Data Peserta" route="peserta.index" icon="ti ti-plant"></x-sidebar.links>
                            <x-sidebar.links title="Daftar Tugas" route="penugasans.index" icon="ti ti-file-text"></x-sidebar.links>
                            <x-sidebar.links title="Laporan Akhir" route="laporan-akhir.index" icon="ti ti-file-text"></x-sidebar.links>
                            @else
                            {{-- Menu default untuk Peserta atau role lainnya jika diperlukan --}}
                            <x-sidebar.links title="Daftar Tugas" route="penugasans.index" icon="ti ti-file-text"></x-sidebar.links>
                            <x-sidebar.links title="Laporan Akhir" route="laporan-akhir.index" icon="ti ti-file-text"></x-sidebar.links>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
