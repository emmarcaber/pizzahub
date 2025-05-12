<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@pizzahub.com',
                'password' => password_hash('hello123!', PASSWORD_DEFAULT),
                'role' => 'admin',
                'address' => '123 Admin Street',
                'phone' => '9123456789',
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@pizzahub.com',
                'password' => password_hash('hello123!', PASSWORD_DEFAULT),
                'role' => 'customer',
                'address' => '456 Customer Avenue',
                'phone' => '9876543211',
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
