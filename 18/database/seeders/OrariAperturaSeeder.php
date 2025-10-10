<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrariApertura;

class OrariAperturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orari = [
            [
                'giorno_settimana' => 1,
                'ora_inizio_mattina' => null,
                'ora_fine_mattina' => null,
                'ora_inizio_pomeriggio' => null,
                'ora_fine_pomeriggio' => null,
                'aperto' => 0,
            ],
            [
                'giorno_settimana' => 2,
                'ora_inizio_mattina' => null,
                'ora_fine_mattina' => null,
                'ora_inizio_pomeriggio' => '14:00',
                'ora_fine_pomeriggio' => '20:00',
                'aperto' => 1,
            ],
            [
                'giorno_settimana' => 3,
                'ora_inizio_mattina' => '09:00',
                'ora_fine_mattina' => '13:00',
                'ora_inizio_pomeriggio' => '14:00',
                'ora_fine_pomeriggio' => '20:00',
                'aperto' => 1,
            ],
            [
                'giorno_settimana' => 4,
                'ora_inizio_mattina' => '09:00',
                'ora_fine_mattina' => '13:00',
                'ora_inizio_pomeriggio' => '14:00',
                'ora_fine_pomeriggio' => '20:00',
                'aperto' => 1,
            ],
            [
                'giorno_settimana' => 5,
                'ora_inizio_mattina' => '09:00',
                'ora_fine_mattina' => '13:00',
                'ora_inizio_pomeriggio' => '14:00',
                'ora_fine_pomeriggio' => '20:00',
                'aperto' => 1,
            ],
            [
                'giorno_settimana' => 6,
                'ora_inizio_mattina' => '09:00',
                'ora_fine_mattina' => '13:00',
                'ora_inizio_pomeriggio' => '14:00',
                'ora_fine_pomeriggio' => '20:00',
                'aperto' => 1,
            ],
            [
                'giorno_settimana' => 7,
                'ora_inizio_mattina' => '09:00',
                'ora_fine_mattina' => '13:00',
                'ora_inizio_pomeriggio' => null,
                'ora_fine_pomeriggio' => null,
                'aperto' => 1,
            ]
        ];
        foreach ($orari as $orario) {
            OrariApertura::updateOrCreate([
                'giorno_settimana' => $orario['giorno_settimana']
            ], $orario);
        }
    }
}
