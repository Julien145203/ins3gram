<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class RecipeModel extends Model
{
    use DataTableTrait;
    protected $table            = 'recipe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','slug', 'alcool','id_user','description'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $beforeInsert = ['setInsertValidationRules','validateAlcool'];
    protected $beforeUpdate = ['setUpdateValidationRules','validateAlcool'];

    protected function setInsertValidationRules(array $data) {
        $this->validationRules = [
            'name'    => 'required|max_length[255]|is_unique[recipe.name]',
            'alcool'  => 'permit_empty|in_list[0,1,on]',
            'id_user' => 'permit_empty|integer',
            'description' => 'permit_empty',
        ];
        return $data;
    }
    protected function setUpdateValidationRules(array $data) {
        $id = $data['data']['id_recipe'] ?? null;
        $this->validationRules = [
            'name'    => "required|max_length[255]|is_unique[recipe.name,id,$id]",
            'alcool'  => 'permit_empty|in_list[0,1,on]',
            'id_user' => 'permit_empty|integer',
            'description' => 'permit_empty',
        ];
        return $data;
    }

    protected $validationMessages = [
        'name' => [
            'required'   => 'Le nom de la recette est obligatoire.',
            'max_length' => 'Le nom de la recette ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cette recette existe déjà.',
        ],
        'alcool' => [
            'in_list' => 'Le champ alcool doit être 0 (sans alcool) ou 1 (avec alcool).',
        ],
        'id_user' => [
            'integer' => 'L’ID de l’utilisateur doit être un nombre.',
        ],
    ];

    /**
     * Récupère depuis la base de données une recette avec tous ses éléments associés (étapes, ingrédients, mots clés, utilisateur, images ...)
     * @param $id null Récupère depuis un ID (mettre null pour récupérer via le slug)
     * @param $slug null Récupère depuis un slug (l'ID doit être à null sinon il est prioritaire)
     * @return array Tableau contenant toutes les informations de notre recette
     */
    public function getFullRecipe($id = null, $slug = null) {
        if($id != null) {
            $recipe = $this->withDeleted()->find($id);
        } elseif($slug != null) {
            $recipe = $this->where('slug', $slug)->withDeleted()->first();
        } else {
            return [];
        }
        if(!$recipe) return [];
        $id_recipe = $recipe['id'];
        //Récupération de l'utilisateur qui à créé la recette (même s'il est désactivé)
        $user = Model('UserModel')->withDeleted()->find($recipe['id_user']);
        unset($recipe['id_user']);
        $recipe['user'] = $user; //on ajoute à notre tableau de recipe
        //Récupération des ingrédients (via la table Quantity)
        $ingredients = Model('QuantityModel')->getQuantityByRecipe($id_recipe);
        $recipe['ingredients'] = $ingredients; //on ajoute au tableau recipe
        //Gestion pour le cas d'un ID (notamment pour l'édition en BO)
        if($id != null) {
            //Récupération des mots clés associés à notre recette
            $recipe_tags = Model('TagRecipeModel')->where('id_recipe', $id_recipe)->findAll();
            //Création d'un tableau à une dimension pour utiliser in_array (directement dans notre tableau recipe)
            foreach ($recipe_tags as $recipe_tag) {
                $recipe['tags'][] = $recipe_tag['id_tag'];
            }
        } else { //Cas d'un SLUG (notamment pour l'affichage en FO)
            $recipe['tags'] = Model('TagRecipeModel')->join('tag','tag_recipe.id_tag = tag.id')->where('id_recipe', $id_recipe)->findAll();
        }
        $mediamodel = Model('MediaModel');
        //Récupération de l'image principale et stocker dans le tableau recipe
        $recipe['mea'] = $mediamodel->where('entity_id', $id_recipe)->where('entity_type', 'recipe_mea')->first();
        //Récupération des images de la recette et stocker dans le tableau recipe
        $recipe['images'] = $mediamodel->where('entity_id', $id_recipe)->where('entity_type', 'recipe')->findAll();
        //Récupération des étapes de la recette
        $steps = Model('StepModel')->where('id_recipe', $id_recipe)->orderBy('order', 'ASC')->findAll();
        $recipe['steps'] = $steps; //on ajoute à notre recette
        return $recipe;
    }


    public function getAllRecipes($filters = [], $orderBy = 'name', $orderDirection = 'ASC', $perPage = 8, $page = 1) {
        // Requête de base identique à votre ancienne version
        $this->select('recipe.id, recipe.name, alcool, slug, media.file_path as mea, COALESCE(AVG(score), 0) as score');
        $this->join('media',' recipe.id = media.entity_id AND media.entity_type = \'recipe_mea\'','left');
        $this->join('opinion',' opinion.id_recipe = recipe.id','left');
        // Ajoutez cette ligne après les JOIN
        $this->applyFilters($filters);
        //$this->orderBy($this->getValidOrderField($orderBy), $orderDirection);
        $this->groupBy('recipe.id');
        $data = $this->paginate($perPage, 'default', $page);
        return [
            'data' => $data,
            'pager' => $this->pager
        ];
    }

    private function applyFilters($filters = []) {
        if (isset($filters['alcool'])) {
            if ($filters['alcool'] == 1) {
                $this->where('recipe.alcool', 1);
            } else if ($filters['alcool'] == 0) {
                $this->where('recipe.alcool', 0);
            }
        }
        if (isset($filters['search']) && !empty(trim($filters['search']))) {
            $search = trim($filters['search']);
            $this->like('recipe.name', $search);
        }
        if (isset($filters['ingredients']) && !empty($filters['ingredients'])) {
            $ingredientIds = $filters['ingredients'];
            $this->join('quantity', 'recipe.id = quantity.id_recipe');
            $this->whereIn('quantity.id_ingredient', $ingredientIds);
            $this->having('COUNT(DISTINCT quantity.id_ingredient) >=', count($ingredientIds));
        }

        $sort = $filters['sort'] ?? 'name_asc';
        switch ($sort) {
            case 'name_asc': $this->orderBy('recipe.name', 'ASC'); break;
            case 'name_desc': $this->orderBy('recipe.name', 'DESC'); break;
            case 'score_desc': $this->orderBy('score', 'DESC'); break;
        }

    }
    public function countAllRecipes($filters = []) {
        // Même construction que getAllRecipes mais pour compter
        $this->select('recipe.id');
        $this->join('media',' recipe.id = media.entity_id AND media.entity_type = \'recipe_mea\'','left');
        $this->join('opinion',' opinion.id_recipe = recipe.id','left');
        $this->applyFilters($filters);
        $this->groupBy('recipe.id');
        return $this->countAllResults();
    }
    private function getValidOrderField($field) {
        $allowedFields = [
            'name' => 'recipe.name',
            'created_at' => 'recipe.created_at',
            'score' => 'score'
        ];
        return $allowedFields[$field] ?? 'recipe.name';
    }

    public function reactive(int $id): bool
    {
        return $this->builder()
            ->where('id', $id)
            ->update(['deleted_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    protected function validateAlcool(array $data) {
        $data['data']['alcool'] = isset($data['data']['alcool']) ? 1 : 0;
        return $data;
    }

    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'recipe.name',
            ],
            'joins' => [
                ['table' => 'user', 'type' => 'LEFT', 'condition' => 'user.id = recipe.id_user'],
                ['table' => 'media', 'type' => 'LEFT', 'condition' => "media.entity_id = recipe.id AND media.entity_type = 'recipe_mea'"],
            ],
            'select' => 'recipe.*, user.username as creator, media.file_path as mea_path',
            'with_deleted' => true
        ];
    }
}