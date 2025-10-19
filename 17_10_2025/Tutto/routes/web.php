<?php

use App\Http\Controllers\VenditaController;
use App\Models\Vendita;
use Illuminate\Support\Facades\Route;

// Mostra vendite.index, form e tabella
Route::get('/', [VenditaController::class, 'index'])->name('vendite.index');

// Redirect a vendite.index dopo aver salvato la vendita
Route::post('/vendite', [VenditaController::class, 'store'])->name('vendite.store');

// Mostra il form per modificare il record
Route::get('/vendite/{id}', [VenditaController::class, 'edit'])->name('vendite.edit');

// Salva la modifica appena fatta sul record
Route::put('/vendite/{id}', [VenditaController::class, 'update'])->name('vendite.update');

// Elimina il record vendita
Route::delete('/vendite/{id}', [VenditaController::class, 'destroy'])->name('vendite.destroy');

// Ajax
Route::post('/calcola-provvigioni', [VenditaController::class, 'calcolaProvvigioni'])->name('vendite.calcola');
