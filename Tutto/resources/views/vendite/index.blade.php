@extends('layouts.app') @section('title', 'Gestione Vendite')
@section('content')
    <h1 class="py-4">Gestione Vendite</h1>

    @include('partials.form') @if ($vendite->isNotEmpty())
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Agente</th>
                    <th>Importo</th>
                    <th>Provvigione</th>
                    <th>Data Vendita</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendite as $v)
                    <tr>
                        <td>{{ $v->agente }}</td>
                        <td>€ {{ number_format($v->importo, 2, ',', '.') }}</td>
                        {{-- 2 decimali separati da , e . separatore migliaia --}}
                        <td>
                            @if ($v->provvigione)
                                € {{ number_format($v->provvigione, 2, ',', '.') }}
                            @else
                                <em>Da calcolare</em>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($v->data_vendita)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('vendite.edit', $v->id) }}" class="btn btn-sm btn-outline-primary">⚙️</a>
                            <form action="{{ route('vendite.destroy', $v->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Sicuro?')">💣</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $vendite->links('pagination::bootstrap-5') }}
            {{-- template paginate compatibile con bootstrap5, deve essere una stringa no classe --}}
            {{-- Laravel crea in automatico previous e next per le pagine dei record --}}
        </div>
    @else
        <p>Nessuna vendita registrata</p>
    @endif
@endsection
