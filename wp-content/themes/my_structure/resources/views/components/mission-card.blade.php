@php
    $missionUrl = $url ?? '#';
    $missionTitle = trim((string) ($title ?? ''));
    $missionSummary = trim(wp_strip_all_tags((string) ($summary ?? '')));
    $missionNeed = trim(wp_strip_all_tags((string) ($need ?? '')));
    $missionAction = trim(wp_strip_all_tags((string) ($action ?? '')));
@endphp

<article class="mission-card {{ !empty($featured) ? 'mission-card--flagship' : '' }}">
    @include('components.media-figure', [
        'image' => theme_mission_media($mission ?? null, $image ?? null),
        'alt' => $missionTitle,
        'ratio' => 'card',
        'loading' => $loading ?? 'lazy',
        'class' => 'mission-card__media',
    ])
    <div class="mission-card__body">
        <h3><a href="{{ esc_url($missionUrl) }}">{{ $missionTitle }}</a></h3>
        @if($missionSummary !== '')
            <p class="mission-card__summary">{{ wp_trim_words($missionSummary, 28, '…') }}</p>
        @endif
        @if($missionNeed !== '' || $missionAction !== '')
            <dl class="mission-card__facts">
                @if($missionNeed !== '')
                    <div><dt>Il bisogno</dt><dd>{{ wp_trim_words($missionNeed, 20, '…') }}</dd></div>
                @endif
                @if($missionAction !== '')
                    <div><dt>L'azione</dt><dd>{{ wp_trim_words($missionAction, 20, '…') }}</dd></div>
                @endif
            </dl>
        @endif
        <div class="mission-card__actions">
            <a class="text-link" href="{{ esc_url($missionUrl) }}">Scopri la missione <span aria-hidden="true">→</span></a>
            <a class="button button--small" href="{{ esc_url($missionUrl . '#donation') }}">Sostieni</a>
        </div>
    </div>
</article>
