<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Tags par type de cocktail
            ['name' => 'Cocktail classique'],
            ['name' => 'Cocktail moderne'],
            ['name' => 'Cocktail tropical'],
            ['name' => 'Cocktail fruité'],
            ['name' => 'Cocktail sec'],
            ['name' => 'Cocktail sucré'],
            ['name' => 'Cocktail amer'],
            ['name' => 'Cocktail rafraîchissant'],

            // Tags par moment
            ['name' => 'Apéritif'],
            ['name' => 'Digestif'],
            ['name' => 'Soirée'],
            ['name' => 'Été'],
            ['name' => 'Hiver'],
            ['name' => 'Fête'],

            // Tags par difficulté
            ['name' => 'Facile'],
            ['name' => 'Moyen'],
            ['name' => 'Difficile'],

            // Tags par caractéristiques
            ['name' => 'Sans alcool'],
            ['name' => 'Faible en alcool'],
            ['name' => 'Fort en alcool'],
            ['name' => 'Vegan'],
            ['name' => 'Sans gluten'],

            // Tags par origine
            ['name' => 'Français'],
            ['name' => 'Italien'],
            ['name' => 'Américain'],
            ['name' => 'Cubain'],
            ['name' => 'Mexicain'],
            ['name' => 'Britannique'],

            // Tags par style de service
            ['name' => 'On the rocks'],
            ['name' => 'Straight up'],
            ['name' => 'Frappé'],
            ['name' => 'Flambé'],
            ['name' => 'Layered'],

            // Tags populaires
            ['name' => 'Tendance'],
            ['name' => 'Signature'],
            ['name' => 'Populaire'],
            ['name' => 'Incontournable'],
        ];

        $this->db->table('tag')->insertBatch($data);
    }
}