@php
    /** @var \Models\Progetto $project */
    $projectTitle = trim((string) ($project->titolo_card ?: $project->title));
    $projectSummary = trim(wp_strip_all_tags((string) ($project->testo_hero ?: $project->project_intro ?: $project->content)));
    $projectUrl = get_permalink($project->id);
    $sectionId = 'flagship-project-' . (int) $project->id;
    $fundraising = $project->getFundraising();
@endphp

<section class="section section--forest flagship-project" aria-labelledby="{{ $sectionId }}" data-reveal>
    <div class="site-container flagship-project__grid">
        <div class="flagship-project__copy">
            <h2 id="{{ $sectionId }}">{{ $projectTitle }}</h2>
            @if($projectSummary !== '')
                <p class="flagship-project__lead">{{ wp_trim_words($projectSummary, 42, '…') }}</p>
            @endif

            <dl class="flagship-project__facts">
                @if(!empty($project->project_location))
                    <div>
                        <dt>Dove</dt>
                        <dd>{{ $project->project_location }}</dd>
                    </div>
                @endif
                <div>
                    <dt>Stato</dt>
                    <dd>{{ $fundraising['status_label'] }}</dd>
                </div>
            </dl>

            <div class="page-hero__actions">
                <a class="button button--light" href="{{ esc_url($projectUrl . '#donation') }}">Sostieni il progetto</a>
                <a class="text-link text-link--light" href="{{ esc_url($projectUrl) }}">Leggi il progetto <span aria-hidden="true">→</span></a>
            </div>
        </div>

        @include('components.media-figure', [
            'image' => theme_mission_media($project, $project->immagine_hero ?: $project->featured_image),
            'alt' => $projectTitle,
            'ratio' => 'hero',
            'class' => 'flagship-project__media',
        ])
    </div>
</section>
