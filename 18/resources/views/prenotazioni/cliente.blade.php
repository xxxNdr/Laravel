@extends('layouts.app')
@section('content')
    <div class="container py-4">
        <h1 class="title-prenotazioni fw-bold text-center mb-5">PRENOTA IL TUO APPUNTAMENTO</h1>

        <div class="row g-3 justify-content-center trattamenti-grid mx-0">
            @foreach ($trattamenti as $t)
                <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center px-2">
                    <button class="btn btn-outline-primary w-100 py-3 trattamento-btn" data-id="{{ $t->id }}">
                        {{ $t->nome }}
                    </button>
                </div>
            @endforeach
        </div>

        <div id="calendar-container" class="my-5 text-center" style="display: none">
            {{-- calendar-container rimane nascosto finchè non si sceglie un trattaemnto --}}
            <h3 class="fw-bold mb-3">SELEZIONA UNA DATA</h3>
            <input type="date" id="data-input" class="form-control w-auto d-inline-block text-center fw-bold">
            <div id="orari-container" class="my-5"></div>
            {{-- orari-container conterrà i bottoni degli orari liberi --}}
        </div>

        <div id="form-container" class="mt-5" style="display: none">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h4 class="card-title text-center mb-4">Completa la prenotazione</h4>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('prenotazioni.store', ['tipo' => 'cliente']) }}" method="POST">
                                @csrf

                                <input type="hidden" name="id_trattamento" id="form-id-trattamento">
                                <input type="hidden" name="data" id="form-data">
                                <input type="hidden" name="ora_inizio" id="form-ora-inizio">

                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome *</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                        value="{{ old('nome') }}" required maxlength="20">
                                </div>
                                <div class="mb-3">
                                    <label for="cognome" class="form-label">Cognome *</label>
                                    <input type="text" class="form-control" id="cognome" name="cognome"
                                        value="{{ old('cognome') }}" required maxlength="30">
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Telefono *</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono"
                                        value="{{ old('telefono') }}" required maxlength="20"
                                        placeholder="+39 123 456 7890">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">Conferma prenotazione</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
