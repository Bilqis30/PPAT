<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_number' => 'DOC/'.fake()->unique()->numerify('####/2026'),
            'document_date' => fake()->date(),
            'title' => fake()->sentence(4),
            'type' => fake()->randomElement(['Surat Masuk', 'Surat Keluar', 'Berkas', 'Laporan']),
            'sub_type' => null,
            'description' => fake()->paragraph(),
            'file_path' => 'archives/'.fake()->uuid().'.pdf',
            'file_name' => fake()->word().'.pdf',
            'file_size' => fake()->numberBetween(10000, 5000000),
            'user_id' => User::factory(),
        ];
    }
}
