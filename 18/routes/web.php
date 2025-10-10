<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrenotazioneController;
use App\Http\Controllers\OrariAperturaController;
use App\Http\Controllers\TrattamentoController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

// ROTTA PUBBLICA PRINCIPALE
/* Cosa fa: Mostra direttamente la vista homepage.blade.php
Accesso: Tutti */

Route::get('/', fn() => Auth::check() ? redirect('/home') : view('welcome'));

// AREA PUBBLICA SENZA LOGIN
/*
/trattamenti -> lista trattamenti
GET/prenotazioni -> form prenotazione
POST/prenotazioni -> salva prenotazione */
Route::get('trattamenti', [TrattamentoController::class, 'index'])->name('trattamenti.index');
Route::get('prenota', [PrenotazioneController::class, 'create'])->name('prenotazioni.create');
Route::post('prenota', [PrenotazioneController::class, 'store'])->name('prenotazioni.store');
Route::get('/api/orari-disponibili', [PrenotazioneController::class, 'ajaxOrariDisponibili'])->name('api.orari-disponibili');;

// AREA PROTETTA CON LOGIN
Route::middleware(['auth'])->group(function () {
    // group raggruppa più rotte che condividono la stessa configurazione

    // DASHBOARD DELL'AMMINISTRATORE
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // CRUD - Gestione dei clienti e delle prenotazioni per l'amministratore
    Route::resource('clienti', ClienteController::class)->except(['show']);
    /* except esclude alcune rotte dal resource()
    escludo 'show' perché non è necessario visualizzare i dettagli di un singolo cliente */

    Route::resource('prenotazioni', PrenotazioneController::class)->except(['create', 'store']);
    Route::resource('trattamenti', TrattamentoController::class)->except(['index']);

    // SUPER ADMIN
    Route::prefix('admin')->name('admin.')->group(function () {
        // prefix aggiunge un prefisso a tutte le rotte del gruppo

        // impostare orari apertura
        Route::get('orari/', [OrariAperturaController::class, 'adminIndex'])->name('orari.index');

        // modifica orari apertura per tutti i giorni
        Route::put('orari/update', [OrariAperturaController::class, 'bulkUpdate'])->name('orari.update');

        // modifica orari apertura per un singolo giorno
        Route::put('orario/{id}/update', [OrariAperturaController::class, 'update'])->name('orario.update');
    });
});

// ROTTE AUTENTICAZIONE
/* Crea automaticamente: Login, Registrazione, Password Reset
 URL: /login, /register, /password/reset, etc. */
Auth::routes();
