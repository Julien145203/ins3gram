<?php

namespace App\Models;

use CodeIgniter\Model;

class TagRecipeModel extends Model
{
    protected $table            = 'tag_recipe';
    protected $primaryKey       = null;
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_recipe','id_tag'];
    protected $validationRules = [
        'id_recipe' => 'required|integer',
        'id_tag'    => 'required|integer',
    ];

    protected $validationMessages = [
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_tag' => [
            'required' => 'Le tag est obligatoire.',
            'integer'  => 'L’ID du tag doit être un nombre.',
        ],
    ];

}
