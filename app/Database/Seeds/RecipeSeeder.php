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
                'slug' => 'bloody-mary',
                'description' => 'Le Bloody Mary est un cocktail plus ou moins fortement pimenté et épicé selon les goûts',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Virgin Cuba Libre',
                'slug' => 'virgin-cuba-libre',
                'description' => 'Un coca avec une tranche de citron',
                'alcool' => 0,
                'id_user' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Mojito',
                'slug' => 'mojito',
                'description' => 'Cocktail cubain rafraîchissant à base de rhum, menthe et citron vert',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Cosmopolitan',
                'slug' => 'cosmopolitan',
                'description' => 'Cocktail élégant à base de vodka et cranberry',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Gin Tonic',
                'slug' => 'gin-tonic',
                'description' => 'Le classique britannique simple et rafraîchissant',
                'alcool' => 1,
                'id_user' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Piña Colada',
                'slug' => 'pina-colada',
                'description' => 'Cocktail tropical crémeux à la noix de coco et ananas',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Margarita',
                'slug' => 'margarita',
                'description' => 'Cocktail mexicain à base de tequila et triple sec',
                'alcool' => 1,
                'id_user' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Whisky Sour',
                'slug' => 'whisky-sour',
                'description' => 'Cocktail américain équilibré entre sucré et acidulé',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Negroni',
                'slug' => 'negroni',
                'description' => 'Apéritif italien amer et sophistiqué',
                'alcool' => 1,
                'id_user' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Moscow Mule',
                'slug' => 'moscow-mule',
                'description' => 'Cocktail pétillant à la vodka et ginger beer',
                'alcool' => 1,
                'id_user' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        $this->db->table('recipe')->insertBatch($data);

        // Quantités pour toutes les recettes
        $quantities = [
            // Bloody Mary (recipe_id = 1)
            ['id_ingredient' => 34, 'id_recipe' => 1, 'id_unit' => 2, 'quantity' => 12],
            ['id_ingredient' => 2, 'id_recipe' => 1, 'id_unit' => 2, 'quantity' => 4],
            ['id_ingredient' => 29, 'id_recipe' => 1, 'id_unit' => 2, 'quantity' => 0.5],
            ['id_ingredient' => 44, 'id_recipe' => 1, 'id_unit' => 7, 'quantity' => 1],

            // Virgin Cuba Libre (recipe_id = 2)
            ['id_ingredient' => 26, 'id_recipe' => 2, 'id_unit' => 2, 'quantity' => 16],
            ['id_ingredient' => 36, 'id_recipe' => 2, 'id_unit' => 13, 'quantity' => 2],

            // Mojito (recipe_id = 3)
            ['id_ingredient' => 8, 'id_recipe' => 3, 'id_unit' => 2, 'quantity' => 6], // Rhum Havana Club
            ['id_ingredient' => 37, 'id_recipe' => 3, 'id_unit' => 15, 'quantity' => 8], // Menthe fraîche
            ['id_ingredient' => 37, 'id_recipe' => 3, 'id_unit' => 14, 'quantity' => 1], // Lime
            ['id_ingredient' => 39, 'id_recipe' => 3, 'id_unit' => 8, 'quantity' => 2], // Sucre blanc
            ['id_ingredient' => 42, 'id_recipe' => 3, 'id_unit' => 22, 'quantity' => 1], // Eau gazeuse

            // Cosmopolitan (recipe_id = 4)
            ['id_ingredient' => 2, 'id_recipe' => 4, 'id_unit' => 2, 'quantity' => 4], // Vodka Grey Goose
            ['id_ingredient' => 16, 'id_recipe' => 4, 'id_unit' => 2, 'quantity' => 2], // Cointreau
            ['id_ingredient' => 31, 'id_recipe' => 4, 'id_unit' => 2, 'quantity' => 3], // Jus de cranberry
            ['id_ingredient' => 29, 'id_recipe' => 4, 'id_unit' => 2, 'quantity' => 1], // Jus de citron

            // Gin Tonic (recipe_id = 5)
            ['id_ingredient' => 4, 'id_recipe' => 5, 'id_unit' => 2, 'quantity' => 5], // Gin Bombay Sapphire
            ['id_ingredient' => 27, 'id_recipe' => 5, 'id_unit' => 2, 'quantity' => 15], // Schweppes Tonic
            ['id_ingredient' => 37, 'id_recipe' => 5, 'id_unit' => 14, 'quantity' => 1], // Lime

            // Piña Colada (recipe_id = 6)
            ['id_ingredient' => 6, 'id_recipe' => 6, 'id_unit' => 2, 'quantity' => 6], // Rhum Bacardi
            ['id_ingredient' => 18, 'id_recipe' => 6, 'id_unit' => 2, 'quantity' => 3], // Malibu (coco)
            ['id_ingredient' => 32, 'id_recipe' => 6, 'id_unit' => 2, 'quantity' => 12], // Jus d'ananas
            ['id_ingredient' => 19, 'id_recipe' => 6, 'id_unit' => 2, 'quantity' => 3], // Baileys (pour la crème)

            // Margarita (recipe_id = 7)
            ['id_ingredient' => 12, 'id_recipe' => 7, 'id_unit' => 2, 'quantity' => 5], // Tequila Jose Cuervo
            ['id_ingredient' => 16, 'id_recipe' => 7, 'id_unit' => 2, 'quantity' => 2], // Cointreau
            ['id_ingredient' => 30, 'id_recipe' => 7, 'id_unit' => 2, 'quantity' => 2], // Jus de lime
            ['id_ingredient' => 43, 'id_recipe' => 7, 'id_unit' => 7, 'quantity' => 1], // Sel

            // Whisky Sour (recipe_id = 8)
            ['id_ingredient' => 9, 'id_recipe' => 8, 'id_unit' => 2, 'quantity' => 6], // Jack Daniel's
            ['id_ingredient' => 29, 'id_recipe' => 8, 'id_unit' => 2, 'quantity' => 2], // Jus de citron
            ['id_ingredient' => 40, 'id_recipe' => 8, 'id_unit' => 2, 'quantity' => 2], // Sirop de sucre

            // Negroni (recipe_id = 9)
            ['id_ingredient' => 4, 'id_recipe' => 9, 'id_unit' => 2, 'quantity' => 3], // Gin Bombay Sapphire
            ['id_ingredient' => 21, 'id_recipe' => 9, 'id_unit' => 2, 'quantity' => 3], // Campari
            ['id_ingredient' => 23, 'id_recipe' => 9, 'id_unit' => 2, 'quantity' => 3], // Martini Rosso

            // Moscow Mule (recipe_id = 10)
            ['id_ingredient' => 1, 'id_recipe' => 10, 'id_unit' => 2, 'quantity' => 5], // Vodka Absolut
            ['id_ingredient' => 28, 'id_recipe' => 10, 'id_unit' => 2, 'quantity' => 15], // Schweppes Ginger Ale
            ['id_ingredient' => 30, 'id_recipe' => 10, 'id_unit' => 2, 'quantity' => 1], // Jus de lime
        ];
        $this->db->table('quantity')->insertBatch($quantities);

        // Tags pour les nouvelles recettes
        $tagRecipes = [
            // Bloody Mary
            ['id_recipe' => 1, 'id_tag' => 14], // Facile
            ['id_recipe' => 1, 'id_tag' => 37], // Incontournable
            ['id_recipe' => 1, 'id_tag' => 21], // Fort en alcool
            ['id_recipe' => 1, 'id_tag' => 4], // Cocktail fruité
            ['id_recipe' => 1, 'id_tag' => 15], // Facile

            // Virgin Cuba Libre
            ['id_recipe' => 2, 'id_tag' => 26], // Cubain
            ['id_recipe' => 2, 'id_tag' => 18], // Sans alcool
            ['id_recipe' => 2, 'id_tag' => 15], // Facile

            // Mojito
            ['id_recipe' => 3, 'id_tag' => 1], // Cocktail classique
            ['id_recipe' => 3, 'id_tag' => 8], // Cocktail rafraîchissant
            ['id_recipe' => 3, 'id_tag' => 26], // Cubain
            ['id_recipe' => 3, 'id_tag' => 12], // Été
            ['id_recipe' => 3, 'id_tag' => 15], // Facile

            // Cosmopolitan
            ['id_recipe' => 4, 'id_tag' => 2], // Cocktail moderne
            ['id_recipe' => 4, 'id_tag' => 4], // Cocktail fruité
            ['id_recipe' => 4, 'id_tag' => 25], // Américain
            ['id_recipe' => 4, 'id_tag' => 11], // Soirée

            // Gin Tonic
            ['id_recipe' => 5, 'id_tag' => 1], // Cocktail classique
            ['id_recipe' => 5, 'id_tag' => 28], // Britannique
            ['id_recipe' => 5, 'id_tag' => 15], // Facile
            ['id_recipe' => 5, 'id_tag' => 9], // Apéritif

            // Piña Colada
            ['id_recipe' => 6, 'id_tag' => 3], // Cocktail tropical
            ['id_recipe' => 6, 'id_tag' => 6], // Cocktail sucré
            ['id_recipe' => 6, 'id_tag' => 12], // Été
            ['id_recipe' => 6, 'id_tag' => 35], // Populaire

            // Margarita
            ['id_recipe' => 7, 'id_tag' => 1], // Cocktail classique
            ['id_recipe' => 7, 'id_tag' => 27], // Mexicain
            ['id_recipe' => 7, 'id_tag' => 15], // Facile
            ['id_recipe' => 7, 'id_tag' => 14], // Fête

            // Whisky Sour
            ['id_recipe' => 8, 'id_tag' => 1], // Cocktail classique
            ['id_recipe' => 8, 'id_tag' => 25], // Américain
            ['id_recipe' => 8, 'id_tag' => 16], // Moyen
            ['id_recipe' => 8, 'id_tag' => 9], // Apéritif

            // Negroni
            ['id_recipe' => 9, 'id_tag' => 1], // Cocktail classique
            ['id_recipe' => 9, 'id_tag' => 7], // Cocktail amer
            ['id_recipe' => 9, 'id_tag' => 24], // Italien
            ['id_recipe' => 9, 'id_tag' => 9], // Apéritif

            // Moscow Mule
            ['id_recipe' => 10, 'id_tag' => 2], // Cocktail moderne
            ['id_recipe' => 10, 'id_tag' => 8], // Cocktail rafraîchissant
            ['id_recipe' => 10, 'id_tag' => 15], // Facile
            ['id_recipe' => 10, 'id_tag' => 12], // Été
        ];
        $this->db->table('tag_recipe')->insertBatch($tagRecipes);

        // Étapes pour les nouvelles recettes
        $steps = [
            // Bloody Mary (déjà existant)
            ['description' => 'Remplir un verre highball de glaçons', 'order' => 1, 'id_recipe' => 1],
            ['description' => 'Verser 4 cl de vodka', 'order' => 2, 'id_recipe' => 1],
            ['description' => 'Ajouter 12 cl de jus de tomate', 'order' => 3, 'id_recipe' => 1],
            ['description' => 'Incorporer 1 cl de jus de citron', 'order' => 4, 'id_recipe' => 1],
            ['description' => 'Assaisonner avec quelques gouttes de sauce Worcestershire et de Tabasco', 'order' => 5, 'id_recipe' => 1],
            ['description' => 'Saler et poivrer', 'order' => 6, 'id_recipe' => 1],
            ['description' => 'Mélanger doucement avec une cuillère', 'order' => 7, 'id_recipe' => 1],
            ['description' => 'Décorer avec une branche de céleri', 'order' => 8, 'id_recipe' => 1],

            // Virgin Cuba Libre (déjà existant)
            ['description' => 'Remplir un verre de glaçons', 'order' => 1, 'id_recipe' => 2],
            ['description' => 'Ajouter 2 cl de jus de citron vert', 'order' => 2, 'id_recipe' => 2],
            ['description' => 'Compléter avec 12 cl de cola', 'order' => 3, 'id_recipe' => 2],
            ['description' => 'Mélanger légèrement', 'order' => 4, 'id_recipe' => 2],
            ['description' => 'Garnir d\'un quartier de citron vert', 'order' => 5, 'id_recipe' => 2],

            // Mojito
            ['description' => 'Placer les feuilles de menthe au fond d\'un verre highball', 'order' => 1, 'id_recipe' => 3],
            ['description' => 'Ajouter le sucre et les quartiers de lime', 'order' => 2, 'id_recipe' => 3],
            ['description' => 'Piler délicatement pour libérer les arômes', 'order' => 3, 'id_recipe' => 3],
            ['description' => 'Remplir le verre de glace pilée', 'order' => 4, 'id_recipe' => 3],
            ['description' => 'Verser le rhum blanc', 'order' => 5, 'id_recipe' => 3],
            ['description' => 'Compléter avec l\'eau gazeuse', 'order' => 6, 'id_recipe' => 3],
            ['description' => 'Mélanger délicatement', 'order' => 7, 'id_recipe' => 3],
            ['description' => 'Décorer avec un brin de menthe', 'order' => 8, 'id_recipe' => 3],

            // Cosmopolitan
            ['description' => 'Refroidir un verre à martini au congélateur', 'order' => 1, 'id_recipe' => 4],
            ['description' => 'Dans un shaker rempli de glaçons, verser la vodka', 'order' => 2, 'id_recipe' => 4],
            ['description' => 'Ajouter le Cointreau', 'order' => 3, 'id_recipe' => 4],
            ['description' => 'Incorporer le jus de cranberry et de citron', 'order' => 4, 'id_recipe' => 4],
            ['description' => 'Shaker énergiquement pendant 15 secondes', 'order' => 5, 'id_recipe' => 4],
            ['description' => 'Filtrer dans le verre à martini', 'order' => 6, 'id_recipe' => 4],
            ['description' => 'Garnir d\'un zeste de citron', 'order' => 7, 'id_recipe' => 4],

            // Gin Tonic
            ['description' => 'Remplir un verre highball de glaçons', 'order' => 1, 'id_recipe' => 5],
            ['description' => 'Verser le gin', 'order' => 2, 'id_recipe' => 5],
            ['description' => 'Compléter avec le tonic', 'order' => 3, 'id_recipe' => 5],
            ['description' => 'Remuer délicatement avec une cuillère', 'order' => 4, 'id_recipe' => 5],
            ['description' => 'Garnir d\'un quartier de lime', 'order' => 5, 'id_recipe' => 5],

            // Piña Colada
            ['description' => 'Dans un blender, ajouter tous les ingrédients avec de la glace', 'order' => 1, 'id_recipe' => 6],
            ['description' => 'Mixer jusqu\'à obtenir une texture lisse et crémeuse', 'order' => 2, 'id_recipe' => 6],
            ['description' => 'Verser dans un verre hurricane', 'order' => 3, 'id_recipe' => 6],
            ['description' => 'Décorer avec une tranche d\'ananas et une cerise', 'order' => 4, 'id_recipe' => 6],
            ['description' => 'Servir avec une paille', 'order' => 5, 'id_recipe' => 6],

            // Margarita
            ['description' => 'Givrer le bord d\'un verre avec du sel', 'order' => 1, 'id_recipe' => 7],
            ['description' => 'Dans un shaker rempli de glace, verser la tequila', 'order' => 2, 'id_recipe' => 7],
            ['description' => 'Ajouter le Cointreau et le jus de lime', 'order' => 3, 'id_recipe' => 7],
            ['description' => 'Shaker énergiquement', 'order' => 4, 'id_recipe' => 7],
            ['description' => 'Filtrer dans le verre givré rempli de glace', 'order' => 5, 'id_recipe' => 7],
            ['description' => 'Garnir d\'un quartier de lime', 'order' => 6, 'id_recipe' => 7],

            // Whisky Sour
            ['description' => 'Dans un shaker, combiner whisky, jus de citron et sirop', 'order' => 1, 'id_recipe' => 8],
            ['description' => 'Ajouter de la glace et shaker vigoureusement', 'order' => 2, 'id_recipe' => 8],
            ['description' => 'Filtrer dans un verre old-fashioned avec glace', 'order' => 3, 'id_recipe' => 8],
            ['description' => 'Garnir d\'une tranche d\'orange et d\'une cerise', 'order' => 4, 'id_recipe' => 8],

            // Negroni
            ['description' => 'Dans un verre old-fashioned rempli de glace', 'order' => 1, 'id_recipe' => 9],
            ['description' => 'Verser le gin, le Campari et le vermouth rouge', 'order' => 2, 'id_recipe' => 9],
            ['description' => 'Remuer délicatement avec une cuillère à bar', 'order' => 3, 'id_recipe' => 9],
            ['description' => 'Garnir d\'un zeste d\'orange', 'order' => 4, 'id_recipe' => 9],

            // Moscow Mule
            ['description' => 'Remplir un mug en cuivre de glace', 'order' => 1, 'id_recipe' => 10],
            ['description' => 'Verser la vodka', 'order' => 2, 'id_recipe' => 10],
            ['description' => 'Ajouter le jus de lime', 'order' => 3, 'id_recipe' => 10],
            ['description' => 'Compléter avec le ginger beer', 'order' => 4, 'id_recipe' => 10],
            ['description' => 'Remuer délicatement', 'order' => 5, 'id_recipe' => 10],
            ['description' => 'Garnir d\'un quartier de lime et menthe', 'order' => 6, 'id_recipe' => 10],
        ];
        $this->db->table('step')->insertBatch($steps);

        // Médias pour les nouvelles recettes (optionnel)
        $media = [
            ['file_path' => 'assets/img/recipes/bloody-mary.jpg', 'entity_id' => 1, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/cuba-libre.jpg', 'entity_id' => 2, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/mojito-cocktail.jpg', 'entity_id' => 3, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/cosmopolitan-cocktail.jpg', 'entity_id' => 4, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/gin-tonic-cocktail.jpg', 'entity_id' => 5, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/pina-colada-cocktail.jpg', 'entity_id' => 6, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/margarita-cocktail.jpg', 'entity_id' => 7, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/whisky-sour-cocktail.jpg', 'entity_id' => 8, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/negroni-cocktail.jpg', 'entity_id' => 9, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['file_path' => 'assets/img/recipes/moscow-mule-cocktail.jpg', 'entity_id' => 10, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ];
        $this->db->table('media')->insertBatch($media);

        // Scores fixes pour chaque recette (1 à 5)
        $scores = [
            // Recette 1 - Bloody Mary
            ['id_recipe' => 1, 'id_user' => 1, 'score' => 3 ],
            ['id_recipe' => 1, 'id_user' => 2, 'score' => 1],

            // Recette 2 - Virgin Cuba Libre
            ['id_recipe' => 2, 'id_user' => 1, 'score' => 0],
            ['id_recipe' => 2, 'id_user' => 2, 'score' => 5],

            // Recette 3 - Mojito
            ['id_recipe' => 3, 'id_user' => 1, 'score' => 4],
            ['id_recipe' => 3, 'id_user' => 2, 'score' => 2],

            // Recette 4 - Cosmopolitan
            ['id_recipe' => 4, 'id_user' => 1, 'score' => 1],
            ['id_recipe' => 4, 'id_user' => 2, 'score' => 1],

            // Recette 5 - Gin Tonic
            ['id_recipe' => 5, 'id_user' => 1, 'score' => 5],
            ['id_recipe' => 5, 'id_user' => 2, 'score' => 3],

            // Recette 6 - Piña Colada
            ['id_recipe' => 6, 'id_user' => 1, 'score' => 2],
            ['id_recipe' => 6, 'id_user' => 2, 'score' => 0],

            // Recette 7 - Margarita
            ['id_recipe' => 7, 'id_user' => 1, 'score' => 4],
            ['id_recipe' => 7, 'id_user' => 2, 'score' => 4],

            // Recette 8 - Whisky Sour
            ['id_recipe' => 8, 'id_user' => 1, 'score' => 3],
            ['id_recipe' => 8, 'id_user' => 2, 'score' => 5],

            // Recette 9 - Negroni
            ['id_recipe' => 9, 'id_user' => 1, 'score' => 0],
            ['id_recipe' => 9, 'id_user' => 2, 'score' => 2],

            // Recette 10 - Moscow Mule
            ['id_recipe' => 10, 'id_user' => 1, 'score' => 5],
            ['id_recipe' => 10, 'id_user' => 2, 'score' => 1],
        ];

        $this->db->table('opinion')->insertBatch($scores);
    }
}