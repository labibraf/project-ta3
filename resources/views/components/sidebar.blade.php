<div>
    <nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="../dashboard/index.html" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        {{-- <img src="{{ asset('template/dist') }}/assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo"> --}}
        <h5 class="text-primary">Aplikasi Manajemen Magang</h5>
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <x-sidebar.links title="Dashboard" route="home" icon="ti ti-dashboard"></x-sidebar.links>
        <x-sidebar.links title="Data peserta" route="peserta.index" icon="ti ti-man"></x-sidebar.links>
        <x-sidebar.links title="Data Bagian" route="bagian.index" icon="ti ti-vector-triangle"></x-sidebar.links>
        @if (Auth::id() == 1 ||Auth::user()->role_id == 1)
        <x-sidebar.links title="Data User" route="users.index" icon="ti ti-users"></x-sidebar.links>
        @endif
      </ul>
    </div>
  </div>
</nav>
</div>
