<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Bloody Mary',
                'description' => 'Le Bloody Mary est un cocktail plus ou moins fortement pimenté et épicé selon les goûts',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Virgin Cuba Libre',
                'description' => 'Un coca avec une tranche de citron',
                'alcool' => 0,
                'id_user' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        $this->db->table('recipe')->insertBatch($data);
        $data = [
            [
                'id_ingredient' => '34',
                'id_recipe' => '1',
                'id_unit' => '2',
                'quantity' => '12',
            ],
            [
                'id_ingredient' => '2',
                'id_recipe' => '1',
                'id_unit' => '2',
                'quantity' => '4',
            ],
            [
                'id_ingredient' => '29',
                'id_recipe' => '1',
                'id_unit' => '2',
                'quantity' => '0.5',
            ],
            [
                'id_ingredient' => '44',
                'id_recipe' => '1',
                'id_unit' => '7',
                'quantity' => '1',
            ],
            [
                'id_ingredient' => '26',
                'id_recipe' => '2',
                'id_unit' => '2',
                'quantity' => '16',
            ],
            [
                'id_ingredient' => '36',
                'id_recipe' => '2',
                'id_unit' => '13',
                'quantity' => '2',
            ],
        ];
        $this->db->table('quantity')->insertBatch($data);
        $data = [
            [
                'id_recipe' => '1',
                'id_tag' => '14'
            ],
            [
                'id_recipe' => '1',
                'id_tag' => '37'
            ],
            [
                'id_recipe' => '1',
                'id_tag' => '21'
            ],
            [
                'id_recipe' => '1',
                'id_tag' => '4'
            ],
            [
                'id_recipe' => '1',
                'id_tag' => '15'
            ],
            [
                'id_recipe' => '2',
                'id_tag' => '26'
            ],
            [
                'id_recipe' => '2',
                'id_tag' => '18'
            ],
            [
                'id_recipe' => '2',
                'id_tag' => '15'
            ],
        ];
        $this->db->table('tag_recipe')->insertBatch($data);
        $data = [
            // Bloody Mary
            [
                'description' => 'Remplir un verre highball de glaçons',
                'order'       => 1,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Verser 4 cl de vodka',
                'order'       => 2,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Ajouter 12 cl de jus de tomate',
                'order'       => 3,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Incorporer 1 cl de jus de citron',
                'order'       => 4,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Assaisonner avec quelques gouttes de sauce Worcestershire et de Tabasco',
                'order'       => 5,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Saler et poivrer',
                'order'       => 6,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Mélanger doucement avec une cuillère',
                'order'       => 7,
                'id_recipe'   => 1,
            ],
            [
                'description' => 'Décorer avec une branche de céleri',
                'order'       => 8,
                'id_recipe'   => 1,
            ],

            // Virgin Cuba Libre
            [
                'description' => 'Remplir un verre de glaçons',
                'order'       => 1,
                'id_recipe'   => 2,
            ],
            [
                'description' => 'Ajouter 2 cl de jus de citron vert',
                'order'       => 2,
                'id_recipe'   => 2,
            ],
            [
                'description' => 'Compléter avec 12 cl de cola',
                'order'       => 3,
                'id_recipe'   => 2,
            ],
            [
                'description' => 'Mélanger légèrement',
                'order'       => 4,
                'id_recipe'   => 2,
            ],
            [
                'description' => 'Garnir d’un quartier de citron vert',
                'order'       => 5,
                'id_recipe'   => 2,
            ],
        ];

        // Insertion en batch
        $this->db->table('step')->insertBatch($data);
    }
}