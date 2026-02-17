@props(['items' => []])

<nav aria-label="breadcrumb" class="breadcrumb-container">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                <i class="bi bi-house-door-fill me-1"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @foreach($items as $item)
            @if($loop->last)
                <li class="breadcrumb-item active d-flex align-items-center" aria-current="page">
                    @if(isset($item['icon']))
                        <i class="bi {{ $item['icon'] }} me-1"></i>
                    @endif
                    <span>{{ $item['label'] }}</span>
                </li>
            @else
                <li class="breadcrumb-item d-flex align-items-center">
                    <a href="{{ $item['url'] ?? '#' }}" class="d-flex align-items-center">
                        @if(isset($item['icon']))
                            <i class="bi {{ $item['icon'] }} me-1"></i>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
