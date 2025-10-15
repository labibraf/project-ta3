<div>
    <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">
                  @if(request()->routeIs('dashboard'))
                    Dashboard {{ auth()->check() && auth()->user()->role_id == 1 ? 'Admin' : (auth()->user()->role_id == 2 ? 'Mentor' : 'Peserta') }}
                  @else
                    {{ $title ?? 'Home' }}
                  @endif
                </h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(!request()->routeIs('dashboard'))
                  <li class="breadcrumb-item" aria-current="page">{{ $subtitle ?? 'Page' }}</li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
</div>
