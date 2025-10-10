<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrariApertura;

class OrariAperturaController extends Controller
{
    /**
     * Vista PUBBLICA per i clienti (solo lettura)
     */

    public function publicIndex()
    {
        $orari = OrariApertura::orderBy('giorno_settimana')->where('aperto', 1)->get();
        return view('orari_apertura.public_index', ['orari' => $orari, 'giorni_settimana' => OrariApertura::GIORNI_SETTIMANA]);
    }

    /**
     * Vista ADMIN per modificare gli orari
     */
    public function adminIndex()
    {
        // mi assicuro che esistano tutti e 7 i giorni
        for ($g = 1; $g <= 7; $g++) {
            OrariApertura::updateOrCreate(
                ['giorno_settimana' => $g],     // condizioni di ricerca
                [                               // attributi da inserire
                    'ora_inizio_mattina' => '09:00',
                    'ora_fine_mattina' => '13:00',
                    'ora_inizio_pomeriggio' => '14:00',
                    'ora_fine_pomeriggio' => '20:00',
                    'aperto' => 1,
                ]
            );
        }
        $orari = OrariApertura::orderBy('giorno_settimana')->get();
        return view('admin.orari_apertura.index', ['orari' => $orari, 'giorni_settimana' => OrariApertura::GIORNI_SETTIMANA]);
    }

    /**
     * Aggiorna un singolo orario
     */
    public function update(Request $request, int $id) // l'id viene dalla rotta URI, uniform resource identifier
    {
        $request->validate([
            'ora_inizio_mattina' => ['nullable', 'date_format:H:i'],
            'ora_fine_mattina' => ['nullable', 'date_format:H:i', 'after:ora_inizio_mattina'],
            // after, vincolo sull'input del form per verificare che l'ora fine mattina sia dopo l'ora inizio mattina
            'ora_inizio_pomeriggio' => ['nullable', 'date_format:H:i'],
            'ora_fine_pomeriggio' => ['nullable', 'date_format:H:i', 'after:ora_inizio_pomeriggio'],
            'aperto' => ['required', 'boolean']
        ]);
        $orario = OrariApertura::findOrFail($id);
        $orario->update($request->only([
            'ora_inizio_mattina',
            'ora_fine_mattina',
            'ora_inizio_pomeriggio',
            'ora_fine_pomeriggio',
            'aperto'
        ]));
        return redirect()->route('admin.orari_apertura.index')->with('success', 'Orario modificato con successo!');
    }

    /**
     * Bulk update - Aggiorna tutta la settimana in una volta
     */

    public function bulkUpdate(Request $request)
    {
        foreach (range(1, 7) as $g) {
            $orario = OrariApertura::where('giorno_settimana', $g)->first(); // primo risultato trovato

            if ($orario) {
                $orario->update([
                    'ora_inizio_mattina' => $request->input("ora_inizio_mattina_$g"),
                    'ora_fine_mattina' => $request->input("ora_fine_mattina_$g"),
                    'ora_inizio_pomeriggio' => $request->input("ora_inizio_pomeriggio_$g"),
                    'ora_fine_pomeriggio' => $request->input("ora_fine_pomeriggio_$g"),
                    'aperto' => $request->has("aperto_$g") ? 1 : 0
                    // has significa controlla se dal form arriva un input con valore, aperto tale giorno
                ]);
            }
        }
        return redirect()->route('admin.orari_apertura.index')->with('success', 'Orari della settimana modificati con successo!');
    }
}
