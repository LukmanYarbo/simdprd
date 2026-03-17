@props(['items' => [], 'title' => 'Page Title'])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb premium-breadcrumb">
        @foreach($items as $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">
                    @if(isset($item['icon']))
                        <i class="{{ $item['icon'] }} me-1"></i>
                    @endif
                    <span>{{ $item['label'] }}</span>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] ?? '#' }}">
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }} me-1"></i>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
