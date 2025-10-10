<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prenotazione extends Model
{
    protected $table = 'prenotazioni';
    protected $fillable = ['id_cliente', 'id_trattamento', 'data', 'ora_inizio', 'ora_fine'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function trattamento()
    {
        return $this->belongsTo(Trattamento::class, 'id_trattamento');
    }
}
