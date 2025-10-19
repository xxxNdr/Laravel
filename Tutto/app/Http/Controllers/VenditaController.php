<?php

namespace App\Http\Controllers;

use App\Models\Vendita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenditaController extends Controller
{
    // Mostra Form e Tebella Vendite
    public function index()
    {
        $vendite = Vendita::orderBy('data_vendita', 'desc')->paginate(10);
        return view(('vendite.index'), ['vendite' => $vendite]);
        // come passo il valore vendite alla view
    }

    // Salvo la vendita nel DB
    public function store(Request $request)
    {
        // validazione
        $request->validate([
            'agente' => 'required|string|max:50',
            'importo' => 'required|numeric|min:1|decimal:0,2',
            'data_vendita' => 'required|date'
        ], ['data_vendita.before_or_equal' => 'La data di vendita non può essere nel futuro']);

        // salvo nel DB
        Vendita::create($request->only(['agente', 'importo', 'data_vendita']));

        //return all'index con success message
        return redirect()->route('vendite.index')->with('success', 'Vendita inserita correttaemnte! Brava!');
    }

    public function edit($id)
    {
        $vendita = Vendita::findOrFail($id);
        return view('vendite.edit', compact('vendita'));
    }

    public function update(Request $request, $id)
    {
        $vendita = Vendita::findOrFail($id);
        $request->validate(
            [
                'agente' => 'required|string|max:50',
                'importo' => 'required|numeric|min:1|decimal:0,2',
                'data_vendita' => 'required|date|before_or_equal:today'
            ],
            ['data_vendita.before_or_equal' => 'La data non può essere nel futuro']
            // messaggio di errore personalizzato
        );
        $vendita->update($request->only(['agente', 'importo', 'data_vendita']));
        $vendita->provvigione = $request->importo * 0.1;
        $vendita->save();
        return redirect()->route('vendite.index')->with('success', 'Vendita aggiornata correttamente');
    }

    public function destroy($id)
    {
        $vendita = Vendita::findOrFail($id);
        $vendita->delete();
        return redirect()->route('vendite.index')->with('success', 'Vendita cancellata adeguatamente');
    }

    public function calcolaProvvigioni()
    {
        $scriptPATH = base_path('calcolo_provvigioni.py');
        shell_exec("py " . escapeshellarg($scriptPATH) . " 2>&1");

        /* CRUCIALE!
        in questo modo ho i valori aggiornati nel database dopo il calcolo di Python
        Se mostro solo l'output di Python il DB non è aggiornato */
        $vendite = DB::table('vendite')->select('id', 'agente', 'importo', 'provvigione', 'data_vendita', 'azione')
            ->orderBy('data_vendita', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Calcolo provvigioni completato con successo.',
            'timestamp' => now(),
            'output' => $vendite
        ]);
    }
}
