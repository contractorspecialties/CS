<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/specialties.json');
        
        if (!file_exists($jsonPath)) {
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        foreach ($data as $group) {
            foreach ($group['items'] as $item) {
                Specialty::updateOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'icon' => $item['icon'],
                        'category' => $group['category'],
                        'aliases' => $item['aliases'] ?? [],
                        'operational_type' => $item['operational_type'] ?? null,
                        'is_regulated' => $item['is_regulated'] ?? false,
                        'sort_order' => $item['sort_order'] ?? 0,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}