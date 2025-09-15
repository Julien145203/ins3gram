<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class FavoriteModel extends Model
{
    use DataTableTrait;
    protected $table            = 'favorite';
    protected $primaryKey       = null;
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_user','id_recipe'];

    protected $validationRules = [
        'id_recipe' => 'required|integer',
        'id_user'   => 'required|integer',
    ];

    protected $validationMessages = [
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_user' => [
            'required' => 'L’utilisateur est obligatoire.',
            'integer'  => 'L’ID de l’utilisateur doit être un nombre.',
        ],
    ];
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['user.username', 'recipe.title'],
            'joins' => [
                [
                    'table' => 'user',
                    'condition' => 'favorite.user_id = user.id',
                    'type' => 'inner'
                ],
                [
                    'table' => 'recipe',
                    'condition' => 'favorite.recipe_id = recipe.id',
                    'type' => 'inner'
                ]
            ],
            'select' => 'favorite.*, user.username, recipe.title',
            'with_deleted' => false,
        ];
    }
}
