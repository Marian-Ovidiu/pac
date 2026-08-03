<div class="empty-state" role="status">
    <span class="empty-state__mark" aria-hidden="true"></span>
    <h2>{{ $title ?? 'Nessun contenuto disponibile' }}</h2>
    @if(!empty($text))<p>{{ $text }}</p>@endif
    @if(!empty($actionUrl) && !empty($actionLabel))
        <a class="button" href="{{ esc_url($actionUrl) }}">{{ $actionLabel }}</a>
    @endif
</div>
