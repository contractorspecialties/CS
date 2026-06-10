<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ingest Master Dynamic Taxonomy Dataset Node
        $this->call(SpecialtySeeder::class);

        // 2. Base Core Test User Node Injection
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
            'is_gc' => false,
            'is_restricted' => false,
        ]);
    }
}