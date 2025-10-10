<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClientiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clienti = [
            [
                'nome' => 'Giulia',
                'cognome' => 'Rossi',
                'telefono' => '3333333333'
            ],
            [
                'nome' => 'Lucia',
                'cognome' => 'Bianchi',
                'telefono' => '4444444444'
            ],
            [
                'nome' => 'Francesca',
                'cognome' => 'Verdi',
                'telefono' => '5555555555'
            ],
            [
                'nome' => 'Josephine',
                'cognome' => 'Gialli',
                'telefono' => '6666666666'
            ]
        ];

        foreach ($clienti as $cliente) {
            Cliente::updateOrCreate([
                'nome' => $cliente['nome'],
                'cognome' => $cliente['cognome'],
                'telefono' => $cliente['telefono']
            ], $cliente);
        }
    }
}
