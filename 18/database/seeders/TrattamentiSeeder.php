<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trattamento;

class TrattamentiSeeder extends Seeder
{
    public function run(): void
    {
        $trattamenti = [
            [
                'nome' => 'Manicure',
                'durata_minuti' => 60,
                'prezzo' => 25,
            ],

            [
                'nome' => 'Pedicure',
                'durata_minuti' => 90,
                'prezzo' => 35,
            ],

            [
                'nome' => 'Massaggio Rilassante',
                'durata_minuti' => 120,
                'prezzo' => 60,
            ],

            [
                'nome' => 'Trattamento Viso',
                'durata_minuti' => 75,
                'prezzo' => 45,
            ]
        ];
        foreach ($trattamenti as $trattamento) {
            Trattamento::updateOrCreate(['nome' => $trattamento['nome']], $trattamento);
        }
        Trattamento::factory()->count(12)->create();
    }
}
