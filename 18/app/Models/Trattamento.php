<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trattamento extends Model
{
    use HasFactory;
    protected $table = 'trattamenti';
    protected $fillable = ['nome', 'durata_minuti', 'prezzo'];
    public $timestamps = true; // Permetti timestamps automatici in Eloquent

    public function prenotazioni(){
        return $this->hasMany(Prenotazione::class, 'id_trattamento');
    }
}
