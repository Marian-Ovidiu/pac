@php
    $progress = $project->getFundraising();
    $hasTarget = $progress['target'] > 0;
    $currency = $progress['currency'];
    $raisedLabel = number_format_i18n($progress['raised'], 0) . ' ' . $currency;
    $targetLabel = number_format_i18n($progress['target'], 0) . ' ' . $currency;
@endphp

<section class="section project-progress" aria-labelledby="project-progress-title">
    <div class="site-container project-progress__grid">
        <div>
            <h2 id="project-progress-title">A che punto siamo</h2>
            <p class="project-progress__intro">Lo stato economico viene aggiornato soltanto quando budget e importi sono verificati.</p>
        </div>

        <div class="project-progress__status">
            <p class="project-progress__state">{{ $progress['status_label'] }}</p>

            @if($hasTarget)
                <dl class="project-progress__amounts">
                    <div>
                        <dt>Raccolti</dt>
                        <dd>{{ $raisedLabel }}</dd>
                    </div>
                    <div>
                        <dt>Obiettivo</dt>
                        <dd>{{ $targetLabel }}</dd>
                    </div>
                </dl>
                <progress
                    value="{{ $progress['raised'] }}"
                    max="{{ $progress['target'] }}"
                    aria-label="Avanzamento della raccolta: {{ $raisedLabel }} su {{ $targetLabel }}">
                    {{ $progress['percentage'] }}%
                </progress>
                <p class="project-progress__percentage">{{ $progress['percentage'] }}% dell’obiettivo verificato</p>
            @else
                <p class="project-progress__pending">Il budget completo è in preparazione. Non mostriamo una percentuale finché l’obiettivo economico non è confermato.</p>
            @endif

            @if($progress['note'] !== '')
                <div class="project-progress__note rich-text">{!! wp_kses_post(wpautop($progress['note'])) !!}</div>
            @endif

            <a class="button" href="#donation">Contribuisci al progetto</a>
        </div>
    </div>
</section>
