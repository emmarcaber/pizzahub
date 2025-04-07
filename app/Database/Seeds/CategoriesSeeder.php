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
                'created_at' => Time::now()
            ],
            [
                'name' => 'Specialty',
                'description' => 'Our chef\'s special creations',
                'created_at' => Time::now()
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Meat-free delicious options',
                'created_at' => Time::now()
            ],
            [
                'name' => 'Vegan',
                'description' => 'Plant-based pizza options',
                'created_at' => Time::now()
            ]
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
