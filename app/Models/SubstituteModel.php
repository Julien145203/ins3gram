<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class SubstituteModel extends Model
{
    use DataTableTrait;
    protected $table            = 'substitute';
    protected $primaryKey       = null;
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_ingredient_base','id_ingredient_sub'];
    protected $validationRules = [
        'id_ingredient_base' => 'required|integer',
        'id_ingredient_sub'  => 'required|integer|different[id_ingredient_base]',
    ];

    protected $validationMessages = [
        'id_ingredient_base' => [
            'required' => 'L’ingrédient de base est obligatoire.',
            'integer'  => 'L’ID de l’ingrédient de base doit être un nombre.',
        ],
        'id_ingredient_sub' => [
            'required'  => 'L’ingrédient substitut est obligatoire.',
            'integer'   => 'L’ID de l’ingrédient substitut doit être un nombre.',
            'different' => 'L’ingrédient substitut doit être différent de l’ingrédient de base.',
        ],
    ];
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['ingredient.name', 'substitute_ingredient.name'],
            'joins' => [
                [
                    'table' => 'ingredient as ingredient',
                    'condition' => 'substitute.id_ingredient = ingredient.id',
                    'type' => 'inner'
                ],
                [
                    'table' => 'ingredient as substitute_ingredient',
                    'condition' => 'substitute.id_substitute = substitute_ingredient.id',
                    'type' => 'inner'
                ]
            ],
            'select' => 'substitute.*, ingredient.name as ingredient_name, substitute_ingredient.name as substitute_name',
            'with_deleted' => false,
        ];
    }
}
// TODO:Substitute columns
