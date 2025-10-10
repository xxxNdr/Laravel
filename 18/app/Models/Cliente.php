<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clienti';
    protected $fillable = ['nome', 'cognome', 'telefono'];

    public function prenotazioni()
    {
        return $this->hasMany('App\Models\Prenotazione', 'id_cliente');
    }
}
