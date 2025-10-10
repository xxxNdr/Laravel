<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/*
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trattamento>
 */

class TrattamentoFactory extends Factory
{
    protected $model = \App\Models\Trattamento::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->words(2, true),
            // true significa ritorna le parole come una stringa separata da spazi invece di un array
            'durata_minuti' => $this->faker->numberBetween(15, 120),
            'prezzo' => $this->faker->numberBetween(30, 200),
        ];
    }
}
