<?php

namespace App\Models;

use CodeIgniter\Model;

class IngredientModel extends Model
{
    protected $table            = 'ingredient';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','description','id_brand','id_categ'];
    protected $validationRules = [
        'name'      => 'required|max_length[255]|is_unique[ingredient.name,id,{id}]',
        'description' => 'permit_empty|string',
        'id_categ'  => 'permit_empty|integer',
        'id_brand'  => 'permit_empty|integer',
    ];

    protected $validationMessages = [
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

}
