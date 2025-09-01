<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'ml'],
            ['name' => 'cl'],
            ['name' => 'dl'],
            ['name' => 'l'],
            ['name' => 'gouttes'],
            ['name' => 'trait'],
            ['name' => 'pincée'],
            ['name' => 'cuillère à café'],
            ['name' => 'cuillère à soupe'],
            ['name' => 'verre'],
            ['name' => 'shot'],
            ['name' => 'oz'],
            ['name' => 'tranche'],
            ['name' => 'quartier'],
            ['name' => 'feuille'],
            ['name' => 'brin'],
            ['name' => 'zeste'],
            ['name' => 'unité'],
            ['name' => 'poignée'],
            ['name' => 'dash'],
            ['name' => 'splash'],
            ['name' => 'top'],
        ];

        $this->db->table('unit')->insertBatch($data);
    }
}