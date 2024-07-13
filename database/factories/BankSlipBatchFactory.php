<?php

namespace Database\Factories;

use App\Enums\BankSlipStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankSlipBatch>
 */
class BankSlipBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'file_path' => 'batches/'.fake()->unique()->uuid.'.csv',
            'bank_slips_count' => fake()->numberBetween(1, 10000),
        ];
    }

    public function processing(): self
    {
        return $this->state([
            'status' => BankSlipStatus::Processing(),
            'processing_attempts' => fake()->numberBetween(1, 3),
            'processing_started_at' => now(),
        ]);
    }

    public function processed(): self
    {
        return $this->state([
            'status' => BankSlipStatus::Processed(),
            'processing_attempts' => 1,
            'processing_started_at' => now(),
            'processing_finished_at' => now()->addSeconds(fake()->numberBetween(1, 60)),
        ]);
    }

    public function failed(): self
    {
        return $this->state([
            'status' => BankSlipStatus::Failed(),
            'processing_attempts' => 1,
            'processing_started_at' => now(),
        ]);
    }
}
