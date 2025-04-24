<!DOCTYPE html>
<html lang="{{ function_exists('pll_current_language') ? pll_current_language() : 'it' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Sito ufficiale PAC - Project Africa Conservation, dedicato alla protezione della fauna e allo sviluppo sociale.')">
    <title>@yield('title', 'PAC - Project Africa Conservation')</title>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "Project Africa Conservation",
          "url": "https://project-africa-conservation.org",
          "logo": "https://project-africa-conservation.org/wp-content/uploads/2024/12/cropped-pittogramma-1.png",
          "sameAs": [
            "https://www.facebook.com/15kZKmU4gr/",
            "https://www.instagram.com/pacitalia?igsh=MWkycW1lZnRmNnAxMA==",
            "https://www.linkedin.com/in/project-africa-conservation-a-p-s-b81a95340/"
          ]
        }
    </script>
    <link rel="canonical" href="{{ get_permalink() }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300&display=swap" rel="stylesheet">
    @yield('head')
</head>
<body class="flex flex-col min-h-screen font-nunitoSansRegular">
    <?php wp_head(); ?>
    @widget('HeaderMenu')
    {{-- @widget('LanguageMenu')
    @switch(pll_current_language())
        @case('it')   @widget('HeaderMenu')         @break
        @case('en')   @widget('HeaderMenuEnglish')  @break
        @case('fr')   @widget('HeaderMenuFrancais') @break
        @case('de')   @widget('HeaderMenuDeutsch')  @break
        @default      @widget('HeaderMenu')         @break
    @endswitch --}}

    <main class="flex-grow main">
        @yield('content')
    </main>

    <footer class="text-white">
        @switch(pll_current_language())
            @case('it')   @widget('FooterMenu')         @break
            {{-- @case('en')   @widget('FooterMenuEnglish')  @break
            @case('fr')   @widget('FooterMenuFrancais') @break
            @case('de')   @widget('FooterMenuDeutsch')  @break --}}
            @default      @widget('FooterMenu')         @break
        @endswitch
    </footer>
    @yield('scripts')
    <?php wp_footer(); ?>
</body>
</html>
