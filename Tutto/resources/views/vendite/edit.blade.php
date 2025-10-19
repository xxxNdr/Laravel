@extends('layouts.app')
@section('title', 'Modifica Vendita')
@section('content')

    <h1 class="py-4">Modifica Vendita</h1>
    <form action="{{ route('vendite.update', $vendita->id) }}" method="POST" class="mb-4">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="agente" placeholder="Agente" required class="form-control"
                    value="{{ old('agente', $vendita->agente) }}">
            </div>
            <div class="col-md-4">
                <input type="number" name="importo" placeholder="Importo" step="0.01" required class="form-control"
                    value="{{ old('importo', $vendita->importo) }}" min="1">
            </div>
            <div class="col-md-4">
                <input type="date" name="data_vendita" required class="form-control"
                    value="{{ old('data_vendita', $vendita->data_vendita) }}">
            </div>
        </div>
        <div class="d-flex gap-2 justify_content-center">
            <button type="submit" class="btn btn-primary">Aggiorna Vendita</button>
            <a href="{{ route('vendite.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
@endsection
