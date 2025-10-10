@extends('layouts.app')
@section('content')
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12">
            <div class="glass-card rounded-4 p-4 p-md-5 text-center min-vh-100 d-flex flex-column justify-content-center">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-auto" style="max-width: 500px;"
                        role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <h1 class="welcome-title fw-bold text-dark mb-5 mb-xl-7">BENVENUTO IN ESTETICAS</h1>
                <div class="row justify-content-center mt-5 mt-xl-7">
                    <div class="col-10 col-sm-8 col-md-6 col-lg-4">
                        <a href="{{ route('prenotazioni.create', ['tipo' => 'cliente']) }}"
                            class="btn btn-outline-primary btn-lg rounded-pill w-100 py-3 welcome-btn">
                            Prenota ora
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
