<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ config('APP_NAME') }}</title>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/static/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/static/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/static/favicon-16x16.png" sizes="16x16">
    <link rel="shortcut icon" href="/static/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Overseer">
    <meta name="application-name" content="Overseer From API">
    <meta name="theme-color" content="#ffffff">
    @auth
      <script type="application/json" id="preloaded_json">
        {!! $preloadedJson !!}
      </script>
    @endauth
  </head>
  <body>
    <div id="app"></div>
    <script src="//api.yiin.lt/socket.io.js"></script>
    <script src="/app.js"></script>
  </body>
</html>
