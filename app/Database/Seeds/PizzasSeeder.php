<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PizzasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'category_id' => 1,
                'name' => 'Margherita',
                'description' => 'Classic tomato sauce, mozzarella, and basil',
                'price' => 9.99,
                'is_available' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'category_id' => 1,
                'name' => 'Pepperoni',
                'description' => 'Tomato sauce, mozzarella, and pepperoni',
                'price' => 11.99,
                'is_available' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'category_id' => 2,
                'name' => 'BBQ Chicken',
                'description' => 'BBQ sauce, chicken, red onions, and cilantro',
                'price' => 13.99,
                'is_available' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'category_id' => 3,
                'name' => 'Vegetarian Delight',
                'description' => 'Tomato sauce, mozzarella, bell peppers, mushrooms, and olives',
                'price' => 12.99,
                'is_available' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'category_id' => 4,
                'name' => 'Vegan Supreme',
                'description' => 'Vegan cheese, tomato sauce, artichokes, sun-dried tomatoes, and spinach',
                'price' => 14.99,
                'is_available' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ]
        ];

        $this->db->table('pizzas')->insertBatch($data);
    }
}
