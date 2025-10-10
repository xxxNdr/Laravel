<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Esteticas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script type="module" src="{{ asset('prenotazioni.js') }}"></script>
</head>


<body>
    <div class="position-absolute top-0 end-0 p-3 loginbtn">
        @guest
            {{-- Guest, se l'utente non è loggato genera automaticamente l'URL login --}}
            <a href="{{ route('login') }}" class="btn btn-lg btn-outline-dark">Login</a>
        @else
            <a href="{{ route('home') }}" class="btn btn-lg btn-outline-dark me-2">Dashboard</a>
            {{-- se l'utente è loggato, homepage ADMIN --}}
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                {{-- il logout per sicurezza viene fatto POST --}}
                @csrf
                {{-- Token di sicurezza Laravel obbligatorio per tutti i form POST
                Protegge da attacchi CSRF (Cross-Site Request Forgery) --}}
                <button type="submit" class="btn btn-lg btn-outline-dark">Logout</button>
            </form>
        @endguest
    </div>

    <div class="container">
        @yield('content')
    </div>

</body>

</html>
