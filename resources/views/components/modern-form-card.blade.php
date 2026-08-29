@props(['title', 'subtitle' => null, 'icon' => 'ti ti-forms'])
<div class="modern-form-card">
    <div class="form-card-header">
        <div class="icon-box"><i class="{{ $icon }}"></i></div>
        <div class="header-text">
            <h5>{{ $title }}</h5>
            @if($subtitle)<small>{{ $subtitle }}</small>@endif
        </div>
    </div>
    <div class="form-card-body">
        {{ $slot }}
    </div>
    @isset($footer)
        <div class="form-card-footer">
            {{ $footer }}
        </div>
    @endisset
</div>
