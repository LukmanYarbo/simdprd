@props(['title', 'subtitle' => null, 'icon' => null])
<div class="modern-page-header">
    <div class="header-left">
        <h2 class="h4 d-flex align-items-center gap-2">
            @if($icon)<span class="icon-box-sm d-inline-grid place-items-center rounded-3 p-2" style="background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; width:38px; height:38px;"><i class="{{ $icon }}"></i></span>@endif
            {{ $title }}
        </h2>
        @if($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    <div class="header-right d-flex align-items-center gap-2">
        {{ $slot }}
    </div>
</div>
