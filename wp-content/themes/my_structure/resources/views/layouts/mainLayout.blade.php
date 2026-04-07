@php
    $seoPluginActive = theme_seo_plugin_active();
    $metaDescription = theme_meta_description();
    $schemaGraph = $seoPluginActive ? null : theme_schema_graph();
    $bodyClasses = implode(' ', get_body_class('flex flex-col min-h-screen font-nunitoSansRegular'));
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
        <script type="application/ld+json">{!! wp_json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300&display=swap" rel="stylesheet">
    @yield('head')
    <?php wp_head(); ?>
</head>
<body class="{{ $bodyClasses }}">
    <?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-black focus:shadow-lg">
        Salta al contenuto principale
    </a>

    <div class="site-header">
        @widget('HeaderMenu')
    </div>

    <main id="main-content" class="main flex-grow" tabindex="-1">
        @yield('content')
    </main>

    <div class="site-footer text-white">
        @widget('FooterMenu')
    </div>

    @yield('scripts')
    <?php wp_footer(); ?>
</body>
</html>
