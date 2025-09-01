<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategIngSeeder extends Seeder
{
    public function run()
    {
        // Insérer d'abord les catégories principales
        $mainCategories = [
            ['id' => 1, 'name' => 'Spiritueux', 'id_categ_parent' => null],
            ['id' => 2, 'name' => 'Liqueurs', 'id_categ_parent' => null],
            ['id' => 3, 'name' => 'Vins et Champagnes', 'id_categ_parent' => null],
            ['id' => 4, 'name' => 'Softs et Sodas', 'id_categ_parent' => null],
            ['id' => 5, 'name' => 'Jus de fruits', 'id_categ_parent' => null],
            ['id' => 6, 'name' => 'Garnitures', 'id_categ_parent' => null],
            ['id' => 7, 'name' => 'Épices et Aromates', 'id_categ_parent' => null],
            ['id' => 8, 'name' => 'Produits laitiers', 'id_categ_parent' => null],
        ];

        $this->db->table('categ_ing')->insertBatch($mainCategories);

        // Puis insérer les sous-catégories
        $subCategories = [
            // Sous-catégories des Spiritueux (parent_id = 1)
            ['name' => 'Vodka', 'id_categ_parent' => 1],
            ['name' => 'Gin', 'id_categ_parent' => 1],
            ['name' => 'Rhum', 'id_categ_parent' => 1],
            ['name' => 'Whisky', 'id_categ_parent' => 1],
            ['name' => 'Tequila', 'id_categ_parent' => 1],
            ['name' => 'Cognac', 'id_categ_parent' => 1],
            ['name' => 'Pastis', 'id_categ_parent' => 1],
            ['name' => 'Brandy', 'id_categ_parent' => 1],

            // Sous-catégories des Liqueurs (parent_id = 2)
            ['name' => 'Liqueurs de fruits', 'id_categ_parent' => 2],
            ['name' => 'Liqueurs crémeuses', 'id_categ_parent' => 2],
            ['name' => 'Amers', 'id_categ_parent' => 2],
            ['name' => 'Vermouths', 'id_categ_parent' => 2],
            ['name' => 'Apéritifs', 'id_categ_parent' => 2],

            // Sous-catégories Vins et Champagnes (parent_id = 3)
            ['name' => 'Champagne', 'id_categ_parent' => 3],
            ['name' => 'Prosecco', 'id_categ_parent' => 3],
            ['name' => 'Vin blanc', 'id_categ_parent' => 3],
            ['name' => 'Vin rouge', 'id_categ_parent' => 3],
            ['name' => 'Vin rosé', 'id_categ_parent' => 3],

            // Sous-catégories Softs et Sodas (parent_id = 4)
            ['name' => 'Sodas', 'id_categ_parent' => 4],
            ['name' => 'Eaux gazeuses', 'id_categ_parent' => 4],
            ['name' => 'Energy drinks', 'id_categ_parent' => 4],
            ['name' => 'Tonics', 'id_categ_parent' => 4],

            // Sous-catégories Jus de fruits (parent_id = 5)
            ['name' => 'Agrumes', 'id_categ_parent' => 5],
            ['name' => 'Fruits rouges', 'id_categ_parent' => 5],
            ['name' => 'Fruits exotiques', 'id_categ_parent' => 5],

            // Sous-catégories Garnitures (parent_id = 6)
            ['name' => 'Fruits frais', 'id_categ_parent' => 6],
            ['name' => 'Herbes aromatiques', 'id_categ_parent' => 6],
            ['name' => 'Décorations', 'id_categ_parent' => 6],
            ['name' => 'Olives et cornichons', 'id_categ_parent' => 6],
        ];

        $this->db->table('categ_ing')->insertBatch($subCategories);
    }
}
