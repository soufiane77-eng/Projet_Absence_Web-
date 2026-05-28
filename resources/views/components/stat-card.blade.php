@props(['title', 'value', 'icon', 'color' => 'primary', 'link' => null])
<div class="card stat-card shadow-sm border-0 h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <p class="text-muted mb-1 small">{{ $title }}</p>
                <h3 class="fw-bold mb-0">{{ $value }}</h3>
            </div>
            <div class="rounded-circle bg-{{ $color }} bg-opacity-10 p-3">
                <i class="fa {{ $icon }} fa-lg text-{{ $color }}"></i>
            </div>
        </div>
        @if($link) <a href="{{ $link }}" class="stretched-link"></a> @endif
    </div>
</div>
