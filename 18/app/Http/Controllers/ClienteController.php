<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clienti = Cliente::all();
        return view('clienti.index', compact('clienti'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clienti.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:20'],
            'cognome' => ['required', 'string', 'max:30'],
            'telefono' => ['required', 'string', 'max:20', 'unique:clienti,telefono'],
            // unique, il numero di telefono deve essere unico per cliente
        ]);
        Cliente::create($request->all());
        return redirect()->route('clienti.index')->with('success', 'Cliente aggiunto con successo!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clienti.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:20'],
            'cognome' => ['required', 'string', 'max:30'],
            'telefono' => ['required', 'string', 'max:20', 'unique:clienti,telefono,' . $id],
            /*
            La regola unique in Laravel accetta parametri aggiuntivi per escludere record specifici durante l'aggiornamento:
            'unique:table,column,except,idColumn'
                */
        ]);
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->only(['nome', 'cognome', 'telefono']));
        return redirect()->route('clienti.index')->with('success', 'Cliente modificato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return redirect()->route('clienti.index')->with('success', 'Cliente eliminato con successo!');
    }
}
