<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $this->call('PermissionSeeder');
        $this->call('UserSeeder');
        $this->call('BrandSeeder');
        $this->call('CategIngSeeder');
        $this->call('UnitSeeder');
        $this->call('IngredientSeeder');
        $this->call('TagSeeder');
    }
}
