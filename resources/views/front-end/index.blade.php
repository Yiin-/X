<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>

    <link rel=stylesheet type=text/css href=https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel=stylesheet>
    <link rel=apple-touch-icon sizes=180x180 href=/static/apple-touch-icon.png>
    <link rel=icon type=image/png href=/static/favicon-32x32.png sizes=32x32>
    <link rel=icon type=image/png href=/static/favicon-16x16.png sizes=16x16>
    <link rel="shortcut icon" href=/static/favicon.ico>
    <meta name=apple-mobile-web-app-title content=Overseer>
    <meta name=application-name content=Overseer>
    <meta name=theme-color content=#ffffff>

@if (config('app.env') === 'production')
    <link href=/static/css/app.css rel=stylesheet>
@endif

@isset ($preloadedJson)
    <script type="application/json" id="preloaded_json">
        {!! json_encode($preloadedJson) !!}
    </script>
@endisset

  </head>
  <body>
    <div id="app"></div>
    <script src="/socket.io.js"></script>

@if (config('app.env') === 'production')
    <script type=text/javascript src=/static/js/manifest.js?{{ str_random() }}></script>
    <script type=text/javascript src=/static/js/vendor.js?{{ str_random() }}></script>
    <script type=text/javascript src=/static/js/app.js?{{ str_random() }}></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-107566913-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-107566913-1');
    </script>
@else
    <script type="text/javascript" src="http://localhost:8080/app.js"></script>
@endif

  </body>
</html>
