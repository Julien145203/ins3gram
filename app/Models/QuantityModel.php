<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class QuantityModel extends Model
{
    use DataTableTrait;
    protected $table            = 'quantity';
    protected $primaryKey       = 'id_ingredient';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_recipe','id_ingredient','id_unit', 'quantity'];

    protected $validationRules = [
        'id_ingredient' => 'required|integer',
        'id_recipe'     => 'required|integer',
        'id_unit'       => 'required|integer',
        'quantity'      => 'required|decimal',
    ];

    protected $validationMessages = [
        'id_ingredient' => [
            'required' => 'L’ingrédient est obligatoire.',
            'integer'  => 'L’ID de l’ingrédient doit être un nombre.',
        ],
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_unit' => [
            'required' => 'L’unité est obligatoire.',
            'integer'  => 'L’ID de l’unité doit être un nombre.',
        ],
        'quantity' => [
            'required' => 'La quantité est obligatoire.',
            'decimal'  => 'La quantité doit être un nombre valide.',
        ],
    ];

    public function getQuantityByRecipe($id_recipe) {
        $this->select('quantity.*, ingredient.name as ingredient, unit.name as unit, media.file_path as image');
        $this->join('ingredient','ingredient.id = quantity.id_ingredient','left');
        $this->join('unit','unit.id = quantity.id_unit','left');
        $this->join('media','ingredient.id = media.entity_id AND media.entity_type = \'ingredient\'','left');
        $this->where('id_recipe', $id_recipe);
        return $this->findAll();
    }
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['ingredient.name', 'quantity.amount', 'quantity.unit'],
            'joins' => [
                [
                    'table' => 'ingredient',
                    'condition' => 'quantity.ingredient_id = ingredient.id',
                    'type' => 'inner'
                ]
            ],
            'select' => 'quantity.*, ingredient.name as ingredient_name',
            'with_deleted' => false,
        ];
    }
}