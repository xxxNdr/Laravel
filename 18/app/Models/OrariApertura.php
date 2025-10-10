<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrariApertura extends Model
{
    protected $table = 'orari_apertura';
    protected $fillable = ['giorno_settimana', 'ora_inizio_mattina', 'ora_fine_mattina', 'ora_inizio_pomeriggio', 'ora_fine_pomeriggio', 'aperto'];
    const GIORNI_SETTIMANA = [1=>'Lunedi', 2=>'Martedi', 3=>'Mercoledi', 4=>'Giovedi', 5=>'Venerdi', 6=>'Sabato', 7=>'Domenica'];
}
