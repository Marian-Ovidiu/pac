<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Il mio sito')</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    @yield('head')
</head>
<body class="flex flex-col min-h-screen">
   <?php wp_head(); ?>
    <header>
        @widget('HeaderMenu')
    </header>

    <main class="flex-grow main">
        @yield('content')
    </main>

    <footer class="text-white">
     {{--   @widget('FooterMenu')--}}
    </footer>
    @yield('scripts')
   <?php wp_footer(); ?>
</body>
</html>
