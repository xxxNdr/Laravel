<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrattamentoFactory extends Factory
{
    protected $model = \App\Models\Trattamento::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->words(2, true),
            // true significa ritorna le parole come una stringa separata da spazi invece di un array
            'durata_minuti' => fake()->numberBetween(15, 120),
            'prezzo' => fake()->numberBetween(30, 200),
        ];
    }
}
