<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>
        @yield('headTitle')
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="@yield('metaDescription')">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="{{ mix('/build/main.css') }}">
    @stack('css')
    <script src="{{ mix('/build/main.js') }}" defer></script>
    @stack('scripts')
    <!-- Matomo -->
    <script type="text/javascript">
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="https://brabyn.matomo.cloud/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.src='//cdn.matomo.cloud/brabyn.matomo.cloud/matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
</head>
<body class="is-preload">

<!-- Wrapper -->
<div id="wrapper">

    <!-- Main -->
    <div id="main">
        <div class="inner">

            <!-- Header -->
            <header id="header">
                <a href="{{ route('tutorial.intro') }}" class="logo"><strong>Dynamic Forms with Laravel</strong></a>
                <ul class="icons">
                    <li>"How to" Guide</li>
                </ul>
            </header>

            <!-- Content -->
            <section>
                @yield('content')
            </section>

        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="inner">

            <!-- Menu -->
            <nav id="menu">
                <header class="major">
                    <h2>Menu</h2>
                </header>
                <ul>
                    <li><a href="{{ route('tutorial.intro') }}" accesskey="i">Introduction</a></li>
                    <li><a href="{{ route('programmer.create', ['locale'=>'en_US']) }}" accesskey="l">Example Form</a></li>
                    <li><a href="{{ route('tutorial.techniques') }}" accesskey="e">General Techniques Used</a></li>
                    <li><a href="{{ route('tutorial.instructions') }}" accesskey="o">Instructions</a></li>
                    <li><a href="{{ route('programmer.list') }}" accesskey="e">Form Entries</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</body>
</html>
