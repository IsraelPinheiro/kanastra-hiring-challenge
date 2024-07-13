<?php

namespace Database\Factories;

use App\Models\BankSlipBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankSlip>
 */
class BankSlipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'debt_id' => fake()->unique()->uuid,
            'bank_slip_batch_id' => BankSlipBatch::factory(),
            'debtor_government_id' => fake()->unique()->numerify(fake()->boolean() ? '###########' : '##############'),
            'debtor_email' => fake()->safeEmail(),
            'amount' => fake()->randomFloat(2, 1, 10000),
            'due_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }

    public function naturalPerson(): self
    {
        return $this->state([
            'debtor_government_id' => fake()->unique()->numerify('###########'),
        ]);
    }

    public function legalPerson(): self
    {
        return $this->state([
            'debtor_government_id' => fake()->unique()->numerify('##############'),
        ]);
    }

    public function notified(): self
    {
        return $this->state([
            'notified_at' => fake()->dateTime(),
        ]);
    }
}
