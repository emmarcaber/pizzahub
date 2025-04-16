<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Classic',
                'description' => 'Traditional pizza favorites',
            ],
            [
                'name' => 'Specialty',
                'description' => 'Our chef\'s special creations',
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Meat-free delicious options',
            ],
            [
                'name' => 'Vegan',
                'description' => 'Plant-based pizza options',
            ]
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
