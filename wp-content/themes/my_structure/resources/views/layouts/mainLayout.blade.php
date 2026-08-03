@php
    $seoPluginActive = theme_seo_plugin_active();
    $metaDescription = theme_meta_description();
    $schemaGraph = $seoPluginActive ? null : theme_schema_graph();
    $canonicalUrl = $seoPluginActive ? null : theme_canonical_url();
    $ogMeta = $seoPluginActive ? null : theme_open_graph_meta();
    $bodyClasses = implode(' ', get_body_class('site-body'));
@endphp
<!DOCTYPE html>
<html {!! function_exists('language_attributes') ? language_attributes() : 'lang="it"' !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ wp_get_document_title() }}</title>
    @if (!$seoPluginActive && $metaDescription)
        <meta name="description" content="{{ esc_attr($metaDescription) }}">
    @endif
    @if (!$seoPluginActive && $schemaGraph)
        <script type="application/ld+json">{!! wp_json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @if (!$seoPluginActive && !empty($canonicalUrl))
        <link rel="canonical" href="{{ esc_url($canonicalUrl) }}">
    @endif
    @if (!$seoPluginActive && !empty($ogMeta))
        @foreach ($ogMeta as $ogProp => $ogContent)
            @if ((string) $ogContent !== '')
                <meta property="{{ esc_attr($ogProp) }}" content="{{ esc_attr($ogContent) }}">
            @endif
        @endforeach
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('head')
    <?php wp_head(); ?>
</head>
<body class="{{ $bodyClasses }}">
    <!--
    THESIS: Quaderno di campo — impatto visibile; rifiuta dashboard, metriche decorative e raccolta fondi generica.
    OWN-WORLD: carta sand e paper, superfici forest, verde PAC, Cormorant editoriale, Inter funzionale e dati reali in DM Mono.
    STORY: il visitatore comprende il bisogno, vede l'azione disponibile e sceglie se approfondire, sostenere o collaborare.
    FIRST VIEWPORT: brand e azione restano leggibili; ogni pagina apre con un solo H1, una promessa concreta e media reale o fallback dichiarato.
    FORM: racconto documentario PAC, direzione approvata “Quaderno di campo — impatto visibile”, seed pinned-ui-ux-art-direction.
    FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, and DESIGN.md
    -->
    <?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
    <a href="#main-content" class="skip-link">Salta al contenuto principale</a>

    @widget('HeaderMenu')

    <main id="main-content" class="site-main" tabindex="-1">
        @yield('content')
    </main>

    @widget('FooterMenu')

    @yield('scripts')
    <?php wp_footer(); ?>
</body>
</html>
