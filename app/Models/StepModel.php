<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class StepModel extends Model
{
    use DataTableTrait;
    protected $table            = 'step';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['description', 'order','id_recipe'];
    protected $validationRules = [
        'description' => 'required|string',
        'order'       => 'required|integer',
        'id_recipe'   => 'required|integer',
    ];

    protected $validationMessages = [
        'description' => [
            'required'  => 'La description est obligatoire.',
        ],
        'order' => [
            'required' => 'L’ordre est obligatoire.',
            'integer'  => 'L’ordre doit être un nombre.',
        ],
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
    ];
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['step.description', 'recipe.title'],
            'joins' => [
                [
                    'table' => 'recipe',
                    'condition' => 'step.recipe_id = recipe.id',
                    'type' => 'inner'
                ]
            ],
            'select' => 'step.*, recipe.title as recipe_title',
            'with_deleted' => false,
        ];
    }
    public function getStepsByIdRecipe($id_recipe) {
        $this->select('step.*');
        $this->where('id_recipe',$id_recipe);
        return $this->findAll();
    }
}
