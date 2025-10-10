<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prenotazione;
use App\Models\Trattamento;
use App\Models\Cliente;
use App\Models\OrariApertura;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class PrenotazioneController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'ajaxOrariDisponibili']);
        // permette a create e store di essere accessibili senza login (per i clienti)
    }

    /*
Tipi di vista per prenotazioni:
ADMIN: vista per l'amministratore che gestisce l'agenda
CLIENTE: vista per i clienti che prenotano online
*/
    private const ADMIN = 'admin';
    private const CLIENTE = 'cliente';

    /*
   Visualizza l'elenco delle prenotazioni
   L'amministratore vede le prenotazioni per gestire l'agenda
   e modificare/cancellare prenotazioni su richiesta dei clienti
   */
    public function index()
    {
        $prenotazioni = Prenotazione::with(['trattamento', 'cliente'])
            ->orderBy('data', 'desc')
            ->orderBy('ora_inizio', 'desc')
            ->get();

        return view('prenotazioni.index', ['prenotazioni' => $prenotazioni]);
    }

    /*
    Mostra il form per creare una nuova prenotazione
    In base al parametro 'tipo' nella query string (?tipo=cliente), decide se mostrare:
    - Vista ADMIN: per gestione agenda (con selezione cliente esistente)
    - Vista CLIENTE: per prenotazioni online (con creazione nuovo cliente)
    */
    public function create(Request $request)
    {
        // Se l'utente è autenticato E non ha specificato ?tipo=cliente, mostra vista admin
        if (Auth::check() && $request->query('tipo') !== self::CLIENTE) {
            return $this->createAdminView();
        }

        // Altrimenti mostra vista cliente (pubblica)
        return $this->createClienteView();
    }

    /*
    Salva una nuova prenotazione nel database
    In base al tipo di vista, richiama il metodo appropriato:
    - ADMIN: prenotazione con cliente esistente
    - CLIENTE: prenotazione con creazione nuovo cliente
    */
    public function store(Request $request)
    {
        $tipo = $this->getKindRequest($request);

        if ($tipo === self::CLIENTE) {
            return $this->storeCliente($request);
        }

        return $this->storeAdmin($request);
    }

    /*
    Visualizza i dettagli di una prenotazione specifica
    Utile per visualizzare tutti i dettagli di una prenotazione
    quando un cliente chiama per modifiche o informazioni.
    */
    public function show(int $id)
    {
        $prenotazione = Prenotazione::with(['trattamento', 'cliente'])->findOrFail($id);
        return view('prenotazioni.show', compact('prenotazione'));
    }

    /*
    Mostra il form per modificare una prenotazione esistente
    Usato sia da ADMIN che da CLIENTE per modificare prenotazioni
    */
    public function edit(int $id)
    {
        $prenotazione = Prenotazione::findOrFail($id);
        $clienti = Cliente::all();
        $trattamenti = Trattamento::all();
        $id_cliente = request('id_cliente', $prenotazione->id_cliente);
        /* ESEMPIO DI COSA FA REQUEST

        1. PRIMO ACCESSO: prenotazione nel DB = 15 Gennaio, ore 14:00
        2. Cliente modifica ONLINE: cambia a 15 Gennaio, ore 16:00  
        3. Clicca "Salva" → le 16:00 sono già occupate
        4. Validazione fallisce → ritorna al form
        5. request('ora_inizio') = "16:00" (valore che il cliente aveva inserito)
        6. $prenotazione->ora_inizio = "14:00" (valore originale dal DB, ancora invariato)
        7. RISULTATO: $ora_inizio = "16:00" (mantiene la scelta del cliente)
        8. $orari_disponibili = ['14:30', '15:00', '15:30'] (orari liberi reali)
        9. Il cliente VEDE: il suo input "16:00" + lista orari disponibili
        10. Il cliente SCEGLIE: un orario libero dalla lista (es: '15:30')
        */
        $idTrattamento = request('id_trattamento', $prenotazione->id_trattamento);
        $data = request('data', $prenotazione->data);
        $orari_disponibili = $this->orari($data, $idTrattamento, $id);

        return view('prenotazioni.edit', compact('prenotazione', 'clienti', 'trattamenti', 'id_trattamento', 'id_cliente', 'data', 'orari_disponibili'));
    }

    /*
    Elabora la modifica di una prenotazione esistente
    Verifica la disponibilità del nuovo orario selezionato
    Gestisce errori di sovrapposizione tramite il trigger del database
    Redirect alla lista con messaggio di successo
    */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'id_cliente' => ['required', 'exists:clienti,id'],
            'id_trattamento' => ['required', 'exists:trattamenti,id'],
            'data' => ['required', 'date', 'after_or_equal:today'], // after_or_equal:today permette una prenotazione da oggi in avanti
            'ora_inizio' => ['required', 'date_format:H:i'],
        ]);

        $prenotazione = Prenotazione::findOrFail($id);

        try {
            $prenotazione->update($request->all());
            return redirect()->route('prenotazioni.index')->with('success', 'Prenotazione modificata con successo!');
        } catch (QueryException $e) {
            // intercetta sovrapposizione dal database con SQLSTATE
            if (($e->getCode() == '45000')) {
                return back()->withErrors(['ora_inizio' => 'L\'orario selezionato è già occupato da una prenotazione.'])->withInput();
            }
            throw $e;
        }
    }

    /*
    Elimina prenotazione dal database per disdette o correzioni
    Utilizzato sia da admin che clienti per cancellare prenotazioni
    */
    public function destroy(int $id)
    {
        $prenotazione = Prenotazione::findOrFail($id);
        $prenotazione->delete();
        return redirect()->route('prenotazioni.index')->with('success', 'Prenotazione cancellata con successo!');
    }

    /*
    Determina il tipo di vista in base alla richiesta
    Legge il parametro 'tipo' dalla query string:
    /prenotazioni/create?tipo=cliente → Vista clienti
    /prenotazioni/create → Vista admin (default)
    */
    private function getKindRequest(Request $request): string
    {
        $requested = $request->query('tipo', self::ADMIN);
        $valid = [self::ADMIN, self::CLIENTE];
        return in_array($requested, $valid) ? $requested : self::ADMIN;
    }

    /*
    Prepara vista per prenotazioni online clienti con trattamenti e orari
    Include lista trattamenti e orari disponibili per la data selezionata
    */
    private function createClienteView()
    {
        $trattamenti = Trattamento::all();
        $idTrattamento = request('id_trattamento');
        $data = request('data', now()->format('Y-m-d'));
        $orari_disponibili = $this->orari($data, $idTrattamento, null);
        return view('prenotazioni.cliente', compact('trattamenti', 'data', 'orari_disponibili', 'idTrattamento'));
    }

    /*
    Crea la vista per la gestione agenda dell'amministratore
    */
    private function createAdminView()
    {
        $clienti = Cliente::all();
        $trattamenti = Trattamento::all();
        $id_trattamento = request('id_trattamento');
        $id_cliente = request('id_cliente');
        $data = request('data', now()->format('Y-m-d'));
        $orari_disponibili = $this->orari($data, $id_trattamento, null);
        return view('prenotazioni.admin', compact('clienti', 'trattamenti', 'data', 'orari_disponibili', 'id_trattamento', 'id_cliente'));
    }

    /*
    Salva prenotazione online cliente con creazione o recupero cliente tramite telefono
    Gestisce validazione dati e controllo disponibilità orario selezionato
    */
    private function storeCliente(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:20'],
            'cognome' => ['required', 'string', 'max:30'],
            'telefono' => ['required', 'string', 'max:20'],
            'id_trattamento' => ['required', 'exists:trattamenti,id'],
            'data' => ['required', 'date', 'after_or_equal:today'],
            'ora_inizio' => ['required', 'date_format:H:i'],
        ]);

        $cliente = Cliente::firstOrCreate(['telefono' => $request->telefono], ['nome' => $request->nome, 'cognome' => $request->cognome]);

        try {
            Prenotazione::create([
                'id_cliente' => $cliente->id,
                'id_trattamento' => $request->id_trattamento,
                'data' => $request->data,
                'ora_inizio' => $request->ora_inizio
            ]);
            return redirect('/')->with('success', 'Prenotazione effettuata con successo!');
        } catch (QueryException $e) {
            if (($e->getCode() == '45000')) {
                return back()->withErrors(['ora_inizio' => 'L\'orario selezionato è già occupato da una prenotazione.'])->withInput();
            }
            throw $e;
        }
    }

    /*
    Salva prenotazione amministratore con cliente esistente selezionato
    Verifica disponibilità orario e gestisce errori sovrapposizione
    */
    private function storeAdmin(Request $request)
    {
        $request->validate([
            'id_cliente' => ['required', 'exists:clienti,id'],
            // exists controlla che l'id_cliente corrisponda a id nella tabella clienti
            'id_trattamento' => ['required', 'exists:trattamenti,id'],
            'data' => ['required', 'date', 'after_or_equal:today'],
            'ora_inizio' => ['required', 'date_format:H:i'],
        ]);

        try {
            Prenotazione::create($request->all());
            return redirect()->route('prenotazioni.index')->with('success', 'Prenotazione effettuata con successo!');
        } catch (QueryException $e) {
            if (($e->getCode() == '45000')) {
                return back()->withErrors(['ora_inizio' => 'L\'orario selezionato è già occupato da una prenotazione'])->withInput();
            }
            throw $e;
        }
    }

    /*
    Recupera orari disponibili per data e trattamento
    */
    private function orari(string $data, ?int $id_trattamento = null, ?int $escludiPrenotazioneId = null): array
    {
        if (!$data || !$id_trattamento) {
            return [];
        }

        $giornoSettimana = Carbon::createFromFormat('Y-m-d', $data)->dayOfWeekIso;
        // dayOfWeekIso restituisce il giorno della settimana come numero (0 = domenica, 1 = lunedi, ecc.)
        $orarioApertura = OrariApertura::find($giornoSettimana);

        if (!$orarioApertura || !$orarioApertura->aperto) {
            return [];
        }

        $trattamento = Trattamento::find($id_trattamento);
        $durata = $trattamento->durata_minuti;
        $orari_teorici = [];

        //Mattina
        if ($orarioApertura->ora_inizio_mattina && $orarioApertura->ora_fine_mattina) {

            $corrente = Carbon::parse($orarioApertura->ora_inizio_mattina);
            $chiusura = Carbon::parse($orarioApertura->ora_fine_mattina);

            while ($corrente->copy()->addMinutes($durata)->lessThanOrEqualTo($chiusura)) {
                $orari_teorici[] = $corrente->format('H:i');
                $corrente->addMinutes($durata);
            }
        }

        //Pomeriggio
        if ($orarioApertura->ora_inizio_pomeriggio && $orarioApertura->ora_fine_pomeriggio) {
            $corrente = Carbon::parse($orarioApertura->ora_inizio_pomeriggio);
            $chiusura = Carbon::parse($orarioApertura->ora_fine_pomeriggio);

            while ($corrente->copy()->addMinutes($durata)->lessThanOrEqualTo($chiusura)) {
                $orari_teorici[] = $corrente->format('H:i');
                $corrente->addMinutes($durata);
            }
        }
        $prenotazioniGiorno = Prenotazione::where('data', $data)
            ->when($escludiPrenotazioneId, fn($q) => $q->where('id', '!=', $escludiPrenotazioneId))
            /* $escludiPrenotazioneId (condizione)
                se è null salta tutto perché la prenotazione è nuova
                se ha un valore (es: 5) esegui la funzione, perché siamo entrati nella fase di modifica prenotazione
                $q (Querybuilder) è la catena della query che sto costruendo runtime! (decido se mettere un altro where mentre è in esecuzione!)
                use($escludiPrenotazioneId) serve per importare la variabile dentro la funzione perché è esterna
                $q->where... = restituisci la query modificata con la condizione WHERE id != 5 per esempio

                Il concetto è: se puoi modificare una prenotazione allora esiste e c'era spazio
                il contrario non è vero.
                Durante la fase di modifica però bisogna temporaneamente escludere lo spazio occupato prima della modifica!
                */
            ->get(['ora_inizio', 'ora_fine']);
        // recupera solo queste colonne per evitare query inutilizzate

        if ($prenotazioniGiorno->isEmpty()) {
            return $orari_teorici;
        }

        $oreLibere = [];

        foreach ($orari_teorici as $o) {
            $nuovoInizio = Carbon::parse($o);
            $nuovoFine = $nuovoInizio->copy()->addMinutes($durata);
            // copy per evitare di modificare l'oggetto originale

            $oreOccupate = false;

            foreach ($prenotazioniGiorno as $p) {
                $esistenteInizio = Carbon::parse($p->ora_inizio);
                $esistenteFine = Carbon::parse($p->ora_fine);
                if ($nuovoInizio->lessthan($esistenteFine) && $esistenteInizio->lessThan($nuovoFine)) {
                    $oreOccupate = true;
                    break;
                }
            }
            if (!$oreOccupate) {
                $oreLibere[] = $o;
            }
        }
        return $oreLibere;
    }

    public function ajaxOrariDisponibili(Request $request)
    {
        $data = $request->query('data');
        $id_trattamento = $request->query('id_trattamento');
        $escludiPrenotazioneId = $request->query('escludiPrenotazioneId');
        /* È utile nelle chiamate AJAX GET perché non sto mandando un form
        ma solo parametri in URL. Senza query laravel cercherebbe il valore
        in GET in POST in route... */
        if (!$data || !$id_trattamento) {
            return response()->json(['error' => 'Dati mancanti'], 400);
        }
        $oreLibere = $this->orari($data, $id_trattamento, $escludiPrenotazioneId);
        return response()->json(['ore_libere' => $oreLibere]);
    }
    /* 1, ajax riceve i tre parametri, il 3 opzionale solo in caso di modifica per autoscludere
    la prenotazione stessa
    2, controlla ci siano i dati minimi $data e $id_trattamento
    3, chiama il metodo orari che controlla gli orari di apertura,
    esclude quelli già prenotati, resstituisce un array di ore disponibili
    4, ritorna la rispsota json = {"ore_libere": ["09:00", "10:00", "11:30"]}
    5, nel front-end con fetch() o axios() ricevo la rispsota per mostrare
    le date-bottoni cliccabili */
}
