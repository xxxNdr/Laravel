<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendita extends Model
{
    use HasFactory;

    protected $table = 'vendite';
    protected $fillable = [
        /* fillable = campi riempibili in assegnazione di massa
        protected = laravel blocca tutti i campi che non sono in questa lista
        NO PROVVIGIONE, viene calcolata da Python
        */
        'agente',
        'importo',
        'data_vendita'
    ];

    
}
