<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Absolut'],
            ['name' => 'Grey Goose'],
            ['name' => 'Bombay Sapphire'],
            ['name' => 'Tanqueray'],
            ['name' => 'Bacardi'],
            ['name' => 'Captain Morgan'],
            ['name' => 'Havana Club'],
            ['name' => 'Cointreau'],
            ['name' => 'Grand Marnier'],
            ['name' => 'Baileys'],
            ['name' => 'Kahlúa'],
            ['name' => 'Martini'],
            ['name' => 'Campari'],
            ['name' => 'Aperol'],
            ['name' => 'Angostura'],
            ['name' => 'Schweppes'],
            ['name' => 'Perrier'],
            ['name' => 'San Pellegrino'],
            ['name' => 'Coca-Cola'],
            ['name' => 'Red Bull'],
            ['name' => 'Monin'],
            ['name' => 'Giffard'],
            ['name' => 'Bols'],
            ['name' => 'DeKuyper'],
            ['name' => 'Smirnoff'],
            ['name' => 'Jack Daniel\'s'],
            ['name' => 'Jim Beam'],
            ['name' => 'Johnnie Walker'],
            ['name' => 'Chivas Regal'],
            ['name' => 'Hennessy'],
            ['name' => 'Rémy Martin'],
            ['name' => 'Dom Pérignon'],
            ['name' => 'Moët & Chandon'],
            ['name' => 'Veuve Clicquot'],
            ['name' => 'Prosecco di Valdobbiadene'],
            ['name' => 'Jose Cuervo'],
            ['name' => 'Patrón'],
            ['name' => 'Malibu'],
            ['name' => 'Pernod'],
            ['name' => 'Pastis 51'],
            ['name' => 'Ricard'],
        ];

        $this->db->table('brand')->insertBatch($data);
    }
}