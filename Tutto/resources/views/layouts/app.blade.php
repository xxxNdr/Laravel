<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>@yield('title', '???')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body>
        <div class="container py-5 d-flex flex-column align-items-center">
            @yield('content')
        </div>
    </body>
</html>