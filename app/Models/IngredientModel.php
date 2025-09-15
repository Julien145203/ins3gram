<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;
use App\Traits\Select2Searchable;

class IngredientModel extends Model
{
    use Select2Searchable;
    use DataTableTrait;

    protected $table            = 'ingredient';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','name','description','id_brand','id_categ'];

    protected $validationRules = [
        'id'        => 'permit_empty|integer', // <-- ajouté pour update
        'name'      => 'required|max_length[255]|is_unique[ingredient.name,id,{id}]',
        'description' => 'permit_empty|string',
        'id_categ'  => 'permit_empty|integer',
        'id_brand'  => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'id' => [
            'integer' => 'L’ID doit être un nombre.',
        ],
        'name' => [
            'required'   => 'Le nom de l’ingrédient est obligatoire.',
            'max_length' => 'Le nom de l’ingrédient ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cet ingrédient existe déjà.',
        ],
        'id_categ' => [
            'integer' => 'L’ID de catégorie doit être un nombre.',
        ],
        'id_brand' => [
            'integer' => 'L’ID de marque doit être un nombre.',
        ],
    ];

    // Configuration pour Select2Searchable
    protected $select2SearchFields = ['name', 'description'];
    protected $select2DisplayField = 'name';
    protected $select2AdditionalFields = ['description', 'id_brand', 'id_categ'];

    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['ingredient.name', 'ingredient.description', 'brand.name', 'categ_ing.name'],
            'joins' => [
                ['table' => 'brand', 'condition' => 'ingredient.id_brand = brand.id', 'type' => 'left'],
                ['table' => 'categ_ing', 'condition' => 'ingredient.id_categ = categ_ing.id', 'type' => 'inner']
            ],
            'select' => 'ingredient.*, brand.name as brand_name, categ_ing.name as categ_name',
            'with_deleted' => false
        ];
    }
}
