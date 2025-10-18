@props(['icon', 'title', 'value', 'subtitle' => null, 'class' => ''])

<div class="card social-widget-card {{ $class }}">
    <div class="card-body text-center">
        <i class="ti {{ $icon }} d-block f-46 text-white mb-2"></i>
        <h3 class="text-white m-0">{{ $value }}</h3>
        <h6 class="text-white mt-2 mb-1">{{ $title }}</h6>
        @if($subtitle)
            <span class="text-white opacity-75">{{ $subtitle }}</span>
        @endif
    </div>
</div>
