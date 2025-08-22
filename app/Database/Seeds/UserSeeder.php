<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email' => 'admin@admin.fr',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'username' => 'admin',
                'first_name' => 'ad',
                'last_name' => 'min',
                'birthdate' => '1960-01-01',
                'id_permission' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'email' => 'user@user.fr',
                'password' => password_hash('user', PASSWORD_DEFAULT),
                'username' => 'user',
                'first_name' => 'us',
                'last_name' => 'er',
                'birthdate' => '1980-10-10',
                'id_permission' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        $this->db->table('user')->insertBatch($data);
    }
}
