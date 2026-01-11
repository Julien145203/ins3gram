<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\RecipeModel;
use App\Models\UserModel;

final class RecipeModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // Configuration des tests
    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = 'App';
    protected $seed = 'Tests\Support\Database\Seeds\RecipeTestSeeder';

    private RecipeModel $recipeModel;
    private UserModel $userModel;
    private int $testUserId;
    private int $rumId;
    private int $vodkaId;
    private int $citronVertId;
    private int $mentheId;
    private int $tagTropicalId;
    private int $tagFacileId;

    private function createCompleteCocktail(): int
    {
        $id_cocktail = $this->recipeModel->insert([
            'name' => 'Mojito Classic',
            'slug' => 'mojito-classic',
            'alcool' => 1,
            'id_user' => $this->testUserId,
            'description' => 'Le célèbre cocktail cubain à base de rhum blanc, menthe fraîche, citron vert et eau gazeuse'
        ]);
        $quantities = [
            ['id_ingredient' => 8, 'id_recipe' => $id_cocktail, 'id_unit' => 2, 'quantity' => 6], // Rhum Havana Club
            ['id_ingredient' => 37, 'id_recipe' => $id_cocktail, 'id_unit' => 15, 'quantity' => 8], // Menthe fraîche
            ['id_ingredient' => 37, 'id_recipe' => $id_cocktail, 'id_unit' => 14, 'quantity' => 1], // Lime
            ['id_ingredient' => 39, 'id_recipe' => $id_cocktail, 'id_unit' => 8, 'quantity' => 2], // Sucre blanc
            ['id_ingredient' => 42, 'id_recipe' => $id_cocktail, 'id_unit' => 22, 'quantity' => 1], // Eau gazeuse
        ];
        Model('App\Models\QuantityModel')->insertBatch($quantities);

        $tagRecipes = [
            ['id_recipe' => $id_cocktail, 'id_tag' => 1], // Cocktail classique
            ['id_recipe' => $id_cocktail, 'id_tag' => 8], // Cocktail rafraîchissant
            ['id_recipe' => $id_cocktail, 'id_tag' => 26], // Cubain
            ['id_recipe' => $id_cocktail, 'id_tag' => 12], // Été
            ['id_recipe' => $id_cocktail, 'id_tag' => 15], // Facile
        ];
        Model("App\Models\TagRecipeModel")->insertBatch($tagRecipes);
        $steps = [
            // Mojito
            ['description' => 'Placer les feuilles de menthe au fond d\'un verre highball', 'order' => 1, 'id_recipe' => $id_cocktail],
            ['description' => 'Ajouter le sucre et les quartiers de lime', 'order' => 2, 'id_recipe' => $id_cocktail],
            ['description' => 'Piler délicatement pour libérer les arômes', 'order' => 3, 'id_recipe' => $id_cocktail],
            ['description' => 'Remplir le verre de glace pilée', 'order' => 4, 'id_recipe' => $id_cocktail],
            ['description' => 'Verser le rhum blanc', 'order' => 5, 'id_recipe' => $id_cocktail],
            ['description' => 'Compléter avec l\'eau gazeuse', 'order' => 6, 'id_recipe' => $id_cocktail],
            ['description' => 'Mélanger délicatement', 'order' => 7, 'id_recipe' => $id_cocktail],
            ['description' => 'Décorer avec un brin de menthe', 'order' => 8, 'id_recipe' => $id_cocktail],
        ];
        Model('App\Models\StepModel')->insertBatch($steps);
        $media = [
            ['file_path' => 'uploads/2025/09/recipe/3/mojito-cocktail.jpg', 'entity_id' => $id_cocktail, 'entity_type' => 'recipe_mea', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ];
        $this->db->table('media')->insertBatch($media);
        return $id_cocktail;
    }
    protected function setUp(): void
    {
        parent::setUp();

        $this->recipeModel = new RecipeModel();

        $this->testUserId = 2;

        $this->rumId = 6;
        $this->vodkaId = 1;
        $this->citronVertId = 36;
        $this->mentheId = 38;

        $this->tagTropicalId = 3;
        $this->tagFacileId = 15;
    }
    public function testCanCreateCocktail()
    {
        $data = [
            'name' => 'Mojito',
            'slug' => 'mojito',
            'alcool' => 1,
            'id_user' => $this->testUserId,
            'description' => 'Cocktail cubain rafraîchissant à base de rhum, menthe et citron vert'
        ];

        $cocktailId = $this->recipeModel->insert($data);

        $this->assertIsInt($cocktailId);
        $this->assertGreaterThan(0, $cocktailId);
        $this->seeInDatabase('recipe', ['name' => 'Mojito', 'alcool' => 1]);
    }
    public function testCanReadCocktail()
    {
        // Créer un cocktail
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Margarita',
            'slug' => 'margarita',
            'alcool' => 1,
            'id_user' => $this->testUserId,
            'description' => 'Cocktail mexicain à base de tequila'
        ]);

        // Lire le cocktail
        $cocktail = $this->recipeModel->find($cocktailId);

        $this->assertIsArray($cocktail);
        $this->assertEquals('Margarita', $cocktail['name']);
        $this->assertEquals('margarita', $cocktail['slug']);
        $this->assertEquals(1, $cocktail['alcool']);
    }
    public function testCanUpdateCocktail()
    {
        // Créer un cocktail
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Piña Colada',
            'slug' => 'pina-colada',
            'alcool' => 1,
            'id_user' => $this->testUserId,
            'description' => 'Version originale'
        ]);

        // Mettre à jour
        $this->recipeModel->update($cocktailId, [
            'id_recipe' => $cocktailId,
            'name' => 'Piña Colada Premium',
            'description' => 'Version améliorée avec rhum vieilli'
        ]);

        // Vérifier
        $cocktail = $this->recipeModel->find($cocktailId);
        $this->assertEquals('Piña Colada Premium', $cocktail['name']);
        $this->assertEquals('Version améliorée avec rhum vieilli', $cocktail['description']);
    }
    public function testCanSoftDeleteCocktail()
    {
        // Créer un cocktail
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Old Fashioned',
            'slug' => 'old-fashioned',
            'alcool' => 1,
            'id_user' => $this->testUserId
        ]);

        // Soft delete
        $this->recipeModel->delete($cocktailId);

        // Ne devrait plus être visible
        $cocktail = $this->recipeModel->find($cocktailId);
        $this->assertNull($cocktail);

        // Devrait être visible avec withDeleted
        $cocktail = $this->recipeModel->withDeleted()->find($cocktailId);
        $this->assertNotNull($cocktail);
        $this->assertNotNull($cocktail['deleted_at']);
    }
    public function testValidationRequiresName()
    {
        $data = [
            'slug' => 'cocktail-sans-nom',
            'alcool' => 1,
            'id_user' => $this->testUserId
        ];

        $result = $this->recipeModel->insert($data);

        $this->assertFalse($result);
        $errors = $this->recipeModel->errors();
        $this->assertArrayHasKey('name', $errors);
    }
    public function testValidationNameMustBeUnique()
    {
        // Créer un premier cocktail
        $this->recipeModel->insert([
            'name' => 'Cosmopolitan',
            'slug' => 'cosmopolitan',
            'alcool' => 1,
            'id_user' => $this->testUserId
        ]);

        // Tenter de créer avec le même nom
        $result = $this->recipeModel->insert([
            'name' => 'Cosmopolitan',
            'slug' => 'cosmopolitan-2',
            'alcool' => 1,
            'id_user' => $this->testUserId
        ]);

        $this->assertFalse($result);
        $errors = $this->recipeModel->errors();
        $this->assertArrayHasKey('name', $errors);
    }
    public function testValidationAlcoolMustBeValid()
    {
        $data = [
            'name' => 'Cocktail test',
            'slug' => 'cocktail-test',
            'alcool' => 'invalid', // Valeur invalide
            'id_user' => $this->testUserId
        ];

        $result = $this->recipeModel->insert($data);

        $this->assertFalse($result);
        $errors = $this->recipeModel->errors();
        $this->assertArrayHasKey('alcool', $errors);
    }
    public function testGetFullRecipeById()
    {
        // Créer un cocktail complet
        $cocktailId = $this->createCompleteCocktail();

        // Récupérer via ID
        $cocktail = $this->recipeModel->getFullRecipe($cocktailId);

        $this->assertNotEmpty($cocktail);
        $this->assertEquals($cocktailId, $cocktail['id']);
        $this->assertArrayHasKey('user', $cocktail);
        $this->assertArrayHasKey('ingredients', $cocktail);
        $this->assertArrayHasKey('tags', $cocktail);
        $this->assertArrayHasKey('steps', $cocktail);
    }
    public function testGetFullRecipeBySlug()
    {
        // Créer un cocktail
        $cocktailId = $this->createCompleteCocktail();
        $cocktail = $this->recipeModel->find($cocktailId);

        // Récupérer via slug
        $fullCocktail = $this->recipeModel->getFullRecipe(null, $cocktail['slug']);

        $this->assertNotEmpty($fullCocktail);
        $this->assertEquals($cocktail['slug'], $fullCocktail['slug']);
        $this->assertArrayHasKey('user', $fullCocktail);
    }
    public function testGetFullRecipeIncludesIngredients()
    {
        $cocktailId = $this->createCompleteCocktail();

        $cocktail = $this->recipeModel->getFullRecipe($cocktailId);

        $this->assertArrayHasKey('ingredients', $cocktail);
        $this->assertCount(5, $cocktail['ingredients']);
    }
    public function testGetFullRecipeIncludesTags()
    {
        $cocktailId = $this->createCompleteCocktail();

        $cocktail = $this->recipeModel->getFullRecipe($cocktailId);

        $this->assertArrayHasKey('tags', $cocktail);
        $this->assertNotEmpty($cocktail['tags']);
    }
    public function testGetFullRecipeIncludesSteps()
    {
        $cocktailId = $this->createCompleteCocktail();

        $cocktail = $this->recipeModel->getFullRecipe($cocktailId);

        $this->assertArrayHasKey('steps', $cocktail);
        $this->assertCount(8, $cocktail['steps']);
        $this->assertEquals('Piler délicatement pour libérer les arômes', $cocktail['steps'][2]['description']);
    }
    public function testGetFullRecipeReturnsEmptyArrayWhenNotFound()
    {
        $cocktail = $this->recipeModel->getFullRecipe(99999);
        $this->assertEmpty($cocktail);
    }
    public function testGetFullRecipeWorksWithSoftDeletedCocktail()
    {
        $cocktailId = $this->createCompleteCocktail();

        // Soft delete
        $this->recipeModel->delete($cocktailId);

        // Devrait quand même récupérer avec withDeleted
        $cocktail = $this->recipeModel->getFullRecipe($cocktailId);

        $this->assertNotEmpty($cocktail);
        $this->assertNotNull($cocktail['deleted_at']);
    }
    public function testGetAllCocktailsReturnsPaginatedResults()
    {
        $this->seed('App\Database\Seeds\RecipeSeeder');

        $result = $this->recipeModel->getAllRecipes([], 'name', 'ASC', 3, 1);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pager', $result);
        $this->assertCount(3, $result['data']);
    }

    public function testGetAllCocktailsFilterByAlcool()
    {
        $this->seed('App\Database\Seeds\RecipeSeeder');

        // Filtrer seulement les cocktails alcoolisés
        $result = $this->recipeModel->getAllRecipes(['alcool' => 1], 'name', 'ASC', 10, 1);
        $this->assertCount(9, $result['data']);

        // Filtrer seulement les mocktails (sans alcool)
        $result = $this->recipeModel->getAllRecipes(['alcool' => 0], 'name', 'ASC', 10, 1);
        $this->assertCount(1, $result['data']);
    }

    public function testGetAllCocktailsSortByName()
    {
        $this->seed('App\Database\Seeds\RecipeSeeder');

        // Trier par nom ASC
        $result = $this->recipeModel->getAllRecipes(['sort' => 'name_asc'], 'name', 'ASC', 10, 1);
        $this->assertEquals('Bloody Mary', $result['data'][0]['name']);
        $this->assertEquals('Whisky Sour', $result['data'][9]['name']);

        // Trier par nom DESC
        $result = $this->recipeModel->getAllRecipes(['sort' => 'name_desc'], 'name', 'DESC', 10, 1);
        $this->assertEquals('Whisky Sour', $result['data'][0]['name']);
    }
    public function testCountAllCocktailsReturnsCorrectCount()
    {
        $this->seed('App\Database\Seeds\RecipeSeeder');

        $count = $this->recipeModel->countAllRecipes();
        $this->assertEquals(10, $count);
    }
    public function testCountAllCocktailsWithFilters()
    {
        $this->seed('App\Database\Seeds\RecipeSeeder');


        // Compter seulement les cocktails alcoolisés
        $count = $this->recipeModel->countAllRecipes(['alcool' => 1]);
        $this->assertEquals(9, $count);

        // Compter seulement les mocktails
        $count = $this->recipeModel->countAllRecipes(['alcool' => 0]);
        $this->assertEquals(1, $count);
    }
    public function testReactiveRestoresSoftDeletedCocktail()
    {
        $this->seed('App\Database\Seeds\RecipeSeeder');


        $this->recipeModel->delete(1);

        // Vérifier soft delete
        $this->assertNull($this->recipeModel->find(1));

        // Réactiver
        $result = $this->recipeModel->reactive(1);
        $this->assertTrue($result);

        // Devrait être visible maintenant
        $cocktail = $this->recipeModel->find(1);
        $this->assertNotNull($cocktail);
        $this->assertNull($cocktail['deleted_at']);
    }
    public function testCreatedAtIsSetOnInsert()
    {
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Blue Lagoon',
            'slug' => 'blue-lagoon',
            'alcool' => 1,
            'id_user' => $this->testUserId
        ]);

        $cocktail = $this->recipeModel->find($cocktailId);

        $this->assertNotNull($cocktail['created_at']);
        $this->assertNotNull($cocktail['updated_at']);
    }
    public function testUpdatedAtIsModifiedOnUpdate()
    {
        // Créer
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Cosmopolitan',
            'slug' => 'cosmopolitan',
            'alcool' => 1,
            'id_user' => $this->testUserId
        ]);

        $cocktail1 = $this->recipeModel->find($cocktailId);
        $originalUpdatedAt = $cocktail1['updated_at'];

        // Attendre 1 seconde
        sleep(1);

        // Mettre à jour
        $this->recipeModel->update($cocktailId, [
            'id_recipe' => $cocktailId,
            'name' => 'Cosmopolitan Premium',
            'description' => 'Avec vodka Grey Goose'
        ]);

        $cocktail2 = $this->recipeModel->find($cocktailId);

        $this->assertNotEquals($originalUpdatedAt, $cocktail2['updated_at']);
    }

    public function testValidateAlcoolNormalizesValue()
    {
        // Tester avec alcool = "on" (checkbox cochée)
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Mai Tai',
            'slug' => 'mai-tai',
            'alcool' => 'on',
            'id_user' => $this->testUserId
        ]);

        $cocktail = $this->recipeModel->find($cocktailId);
        $this->assertEquals(1, $cocktail['alcool']);
    }
    public function testValidateAlcoolSetsZeroWhenMissing()
    {
        // Ne pas envoyer le champ alcool (mocktail)
        $cocktailId = $this->recipeModel->insert([
            'name' => 'Virgin Mojito',
            'slug' => 'virgin-mojito',
            'id_user' => $this->testUserId
        ]);

        $cocktail = $this->recipeModel->find($cocktailId);
        $this->assertEquals(0, $cocktail['alcool']);
    }

    public function testCanCreateMocktail()
    {
        // Test spécifique : créer un mocktail (sans alcool)
        $mocktailId = $this->recipeModel->insert([
            'name' => 'Virgin Cuba Libre',
            'slug' => 'virgin-cuba-libre',
            'description' => 'Un coca avec une tranche de citron',
            'alcool' => 0,
            'id_user' => 2,
        ]);

        $mocktail = $this->recipeModel->find($mocktailId);
        $this->assertEquals(0, $mocktail['alcool']);
        $this->assertEquals('Virgin Cuba Libre', $mocktail['name']);
    }
    public function testCanFilterCocktailsByAlcoholContent()
    {
        // Créer différents types de boissons
        $this->recipeModel->insertBatch([
            ['name' => 'Mojito Classic', 'slug' => 'mojito-classic', 'alcool' => 1, 'id_user' => $this->testUserId],
            ['name' => 'Virgin Mojito', 'slug' => 'virgin-mojito', 'alcool' => 0, 'id_user' => $this->testUserId],
            ['name' => 'Caipirinha', 'slug' => 'caipirinha', 'alcool' => 1, 'id_user' => $this->testUserId],
            ['name' => 'Limonade', 'slug' => 'limonade', 'alcool' => 0, 'id_user' => $this->testUserId]
        ]);

        // Filtrer cocktails alcoolisés
        $alcoholic = $this->recipeModel->getAllRecipes(['alcool' => 1]);
        $this->assertCount(2, $alcoholic['data']);

        // Filtrer mocktails
        $nonAlcoholic = $this->recipeModel->getAllRecipes(['alcool' => 0]);
        $this->assertCount(2, $nonAlcoholic['data']);
    }



}

