<?php

namespace App\Http\Controllers;

use App\Models\Vendita;
use App\Http\Requests\StoreVendita;
use App\Http\Requests\UpdateVendita; 

class VenditaController extends Controller
{
    // Mostra vendite e form
    public function index()
    {
        return view('vendite.index', ['vendite'=>Vendita::impagina(10)]);
    }

    // Salvo la vendita
    public function store(StoreVendita $request)
    {
     Vendita::crea($request->validated());
     /* validate() ritorna un array contenente solo i dati validati da FormRequest */
     return redirect()->route('vendite.index')->with('success', 'Vendita inserita correttamente');
    }

    // Aggiorna la vendita
    public function edit(Vendita $vendita)
    {
        return view('vendite.edit', compact('vendita'));
    }

    // Elimina la vendita
    public function destroy(Vendita $vendita)
    {
        $vendita->delete();
        return redirect()->route('vendite.index')->with('success', 'Vendita cancellata adeguatamente');
    }

    public function calcolaProvvigioni()
    {
        $vendite = Vendita::provvigioni();
        
        return response()->json([
            'success' => true,
            'message' => 'Calcolo provvigioni completato con successo.',
            'timestamp' => now(),
            'output' => $vendite
        ]);
    }
}
