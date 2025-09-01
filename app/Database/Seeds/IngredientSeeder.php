<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Vodkas (categ_id = 9 - Vodka)
            ['name' => 'Vodka Absolut', 'description' => 'Vodka premium suédoise', 'id_categ' => 9, 'id_brand' => 1],
            ['name' => 'Vodka Grey Goose', 'description' => 'Vodka française haut de gamme', 'id_categ' => 9, 'id_brand' => 2],
            ['name' => 'Vodka Smirnoff', 'description' => 'Vodka classique', 'id_categ' => 9, 'id_brand' => 25],

            // Gins (categ_id = 10 - Gin)
            ['name' => 'Gin Bombay Sapphire', 'description' => 'Gin anglais aux 10 plantes', 'id_categ' => 10, 'id_brand' => 3],
            ['name' => 'Gin Tanqueray', 'description' => 'Gin sec londonien', 'id_categ' => 10, 'id_brand' => 4],

            // Rhums (categ_id = 11 - Rhum)
            ['name' => 'Rhum Bacardi Carta Blanca', 'description' => 'Rhum blanc cubain', 'id_categ' => 11, 'id_brand' => 5],
            ['name' => 'Rhum Captain Morgan', 'description' => 'Rhum épicé', 'id_categ' => 11, 'id_brand' => 6],
            ['name' => 'Rhum Havana Club 3 ans', 'description' => 'Rhum cubain ambré', 'id_categ' => 11, 'id_brand' => 7],

            // Whiskies (categ_id = 12 - Whisky)
            ['name' => 'Jack Daniel\'s', 'description' => 'Whiskey américain Tennessee', 'id_categ' => 12, 'id_brand' => 26],
            ['name' => 'Jim Beam', 'description' => 'Bourbon américain', 'id_categ' => 12, 'id_brand' => 27],
            ['name' => 'Johnnie Walker Red Label', 'description' => 'Scotch whisky blend', 'id_categ' => 12, 'id_brand' => 28],

            // Tequilas (categ_id = 13 - Tequila)
            ['name' => 'Tequila Jose Cuervo', 'description' => 'Tequila mixto mexicaine', 'id_categ' => 13, 'id_brand' => 36],
            ['name' => 'Tequila Patrón Silver', 'description' => 'Tequila 100% agave', 'id_categ' => 13, 'id_brand' => 37],

            // Cognacs (categ_id = 14 - Cognac)
            ['name' => 'Hennessy VS', 'description' => 'Cognac français', 'id_categ' => 14, 'id_brand' => 30],
            ['name' => 'Rémy Martin VSOP', 'description' => 'Cognac de qualité supérieure', 'id_categ' => 14, 'id_brand' => 31],

            // Liqueurs de fruits (categ_id = 17 - Liqueurs de fruits)
            ['name' => 'Cointreau', 'description' => 'Liqueur d\'orange premium', 'id_categ' => 17, 'id_brand' => 8],
            ['name' => 'Grand Marnier', 'description' => 'Liqueur d\'orange au cognac', 'id_categ' => 17, 'id_brand' => 9],
            ['name' => 'Malibu', 'description' => 'Liqueur de coco', 'id_categ' => 17, 'id_brand' => 38],

            // Liqueurs crémeuses (categ_id = 18 - Liqueurs crémeuses)
            ['name' => 'Baileys Irish Cream', 'description' => 'Liqueur crémeuse irlandaise', 'id_categ' => 18, 'id_brand' => 10],
            ['name' => 'Kahlúa', 'description' => 'Liqueur de café mexicaine', 'id_categ' => 18, 'id_brand' => 11],

            // Amers (categ_id = 19 - Amers)
            ['name' => 'Campari', 'description' => 'Amer italien rouge', 'id_categ' => 19, 'id_brand' => 13],
            ['name' => 'Aperol', 'description' => 'Apéritif italien', 'id_categ' => 19, 'id_brand' => 14],
            ['name' => 'Angostura Bitter', 'description' => 'Amer aromatique', 'id_categ' => 19, 'id_brand' => 15],

            // Vermouths (categ_id = 20 - Vermouths)
            ['name' => 'Martini Rosso', 'description' => 'Vermouth rouge italien', 'id_categ' => 20, 'id_brand' => 12],
            ['name' => 'Martini Bianco', 'description' => 'Vermouth blanc italien', 'id_categ' => 20, 'id_brand' => 12],

            // Sodas et softs (categ_id = 25 - Sodas)
            ['name' => 'Coca-Cola', 'description' => 'Soda au cola', 'id_categ' => 25, 'id_brand' => 19],
            ['name' => 'Schweppes Tonic', 'description' => 'Eau tonique', 'id_categ' => 25, 'id_brand' => 16],
            ['name' => 'Schweppes Ginger Ale', 'description' => 'Soda au gingembre', 'id_categ' => 25, 'id_brand' => 16],

            // Jus de fruits
            ['name' => 'Jus de citron', 'description' => 'Jus de citron frais', 'id_categ' => 29, 'id_brand' => null],
            ['name' => 'Jus de lime', 'description' => 'Jus de lime fraîche', 'id_categ' => 29, 'id_brand' => null],
            ['name' => 'Jus d\'orange', 'description' => 'Jus d\'orange frais', 'id_categ' => 29, 'id_brand' => null],
            ['name' => 'Jus de cranberry', 'description' => 'Jus de canneberge', 'id_categ' => 30, 'id_brand' => null],
            ['name' => 'Jus d\'ananas', 'description' => 'Jus d\'ananas tropical', 'id_categ' => 31, 'id_brand' => null],

            // Garnitures et décorations
            ['name' => 'Citron', 'description' => 'Citron jaune frais', 'id_categ' => 32, 'id_brand' => null],
            ['name' => 'Lime', 'description' => 'Lime fraîche', 'id_categ' => 32, 'id_brand' => null],
            ['name' => 'Orange', 'description' => 'Orange fraîche', 'id_categ' => 32, 'id_brand' => null],
            ['name' => 'Menthe fraîche', 'description' => 'Feuilles de menthe', 'id_categ' => 33, 'id_brand' => null],
            ['name' => 'Basilic', 'description' => 'Feuilles de basilic frais', 'id_categ' => 33, 'id_brand' => null],

            // Autres ingrédients essentiels
            ['name' => 'Sucre blanc', 'description' => 'Sucre cristallisé blanc', 'id_categ' => 7, 'id_brand' => null],
            ['name' => 'Sirop de sucre', 'description' => 'Sirop simple', 'id_categ' => 7, 'id_brand' => null],
            ['name' => 'Glaçons', 'description' => 'Cubes de glace', 'id_categ' => 8, 'id_brand' => null],
            ['name' => 'Eau gazeuse', 'description' => 'Eau pétillante', 'id_categ' => 26, 'id_brand' => null],
            ['name' => 'Sel', 'description' => 'Sel fin', 'id_categ' => 7, 'id_brand' => null],
        ];

        $this->db->table('ingredient')->insertBatch($data);
    }
}