<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use App\Models\Cliente;
use App\Models\Trattamento;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // statistiche
        $stats = [
            'prenotazioni_oggi' => Prenotazione::whereDate('data', today())->count(),
            'totale_clienti' => Cliente::count(),
            'prenotazioni_in_arrivo' => Prenotazione::whereDate('data', '>=', today())->count(),
            'totale_trattamenti' => Trattamento::count(),
            'incasso_mensile' => Prenotazione::whereYear('data', now()->year)
                ->whereMonth('data', now()->month)
                ->with('trattamento')
                /*
                SELECT * FROM prenotazioni
                SELECT * FROM trattamenti WHERE id IN (
                SELECT id_trattamento FROM prenotazioni
                )
                */
                ->get() // esegue la query e prende tutte le prenotazioni del mese corrente
                ->sum(fn($prenotazione) => $prenotazione->trattamento->prezzo),
            'clienti_nuovi_30gg' => Cliente::whereDate('created_at', '>=', now()->subMonth())->count(),
            // created_at sta per la data di creazione del cliente, now()->subMonth() sottrae 30 giorni alla data corrente
        ];

        $trattamenti_popolari = Trattamento::withCount('prenotazioni')
            // withCount fa una query per ogni trattamento che conta il numero di prenotazioni
            ->orderBy('prenotazioni_count', 'desc')
            ->take(5)
            // limit 5
            ->get();
        return view('home', compact('stats', 'trattamenti_popolari'));
    }
}
