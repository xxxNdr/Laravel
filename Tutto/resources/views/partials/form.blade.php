<form action="{{ route('vendite.store') }}" method="post" class="mb-4">
    @csrf
    <div class="row mb-3">
        <div class="col-md-4">
            <input
                type="text"
                name="agente"
                placeholder="Agente"
                required
                class="form-control"
                value="{{ old('agente') }}"
            />
            <!-- mantiene il valore inserito dall'utente in caso di errore validazione -->
        </div>
        <div class="col-md-4">
            <input
                type="number"
                step="0.01"
                name="importo"
                placeholder="Importo"
                required
                class="form-control"
                value="{{ old('importo') }}"
                min="1"
            />
        </div>
        <div class="col-md-4">
            <input
                type="date"
                name="data_vendita"
                required
                class="form-control"
                value="{{ old('data_vendita', now()->format('Y-m-d')) }}"
            />
            <!-- al primo accesso now() impostato come parametro di default
             mostra la data odierna invece che campo vuoto -->
        </div>
    </div>
    <button type="submit" class="btn btn-primary d-block mx-auto my-4">Aggiungi Vendita</button>
</form>