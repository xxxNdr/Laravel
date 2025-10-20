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

    /*
    STATICO → Agisce su più record o su uno ancora non esistente
    ISTANZA → Agisce su un record già esistente
    */

    // crea una vendita
    public static function crea(array $data)
    {
        return self::create($data);
    }

    // aggiorna la vendita
    public function aggiorna(array $data)
    {
        $this->update($data);
    }

    // aggiorna provvigioni con Python script
    public static function provvigioni()
    {
        shell_exec(sprintf('py "%s" 2>&1', base_path('calcolo_provvigioni.py')));
        return self::orderBy('data_vendita', 'desc')->get();
    }

    // recupera vendite ordinate per view
    public static function impagina($perPage)
    {
        return self::orderBy('data_vendita', 'desc')->paginate($perPage);
    }
}
