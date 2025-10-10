<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trattamento;

class TrattamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trattamenti = Trattamento::all();
        return view('trattamenti.index', compact('trattamenti'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trattamenti.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'durata_minuti' => ['required', 'integer', 'min:15'],
            'prezzo' => ['required', 'numeric', 'min:10']
        ]);
        Trattamento::create($request->all());
        return redirect()->route('trattamenti.index')->with('success', 'Trattamento aggiunto con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $trattamento = Trattamento::with('prenotazioni')->findOrFail($id);
        /* famoso eager loading così nella vista non devo fare una query per ogni prenotazione
        sul foreach. Perché lavora con 2 query, una select all from trattamenti e select all from prenotazioni
        where id_trattamento (FK) IN (trattamenti(id)) */
        return view('trattamenti.show', compact('trattamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trattamento = Trattamento::findOrFail($id);
        return view('trattamenti.edit', compact('trattamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'durata_minuti' => ['required', 'integer', 'min:15'],
            'prezzo' => ['required', 'numeric', 'min:10']
        ]);
        $trattamento = Trattamento::findOrFail($id);
        $trattamento->update($request->only(['nome', 'durata_minuti', 'prezzo']));
        return redirect()->route('trattamenti.index')->with('success', 'Trattamento modificato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $trattamento = Trattamento::findOrFail($id);
        $trattamento->delete();
        return redirect()->route('trattamenti.index')->with('success', 'Trattamento eliminato con successo!');
    }
}
