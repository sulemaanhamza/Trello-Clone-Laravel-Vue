<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bemo</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" async defer></script>
    </head>
    <body class="antialiased">
        <div id="nav">
            <h4>Bemo Coding Test</h4>
            <a href="/download/database-dump">Export Database</a>
        </div>
        <div id="app">
            <board />
        </div>
    </body>
</html>
