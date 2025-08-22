<?php

namespace App\Models;

use CodeIgniter\Model;

class StepModel extends Model
{
    protected $table            = 'step';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['description', 'order','id_recipe'];
    protected $validationRules = [
        'description' => 'required|string|is_unique[step.description,id,{id}]',
        'order'       => 'required|integer',
        'id_recipe'   => 'required|integer',
    ];

    protected $validationMessages = [
        'description' => [
            'required'  => 'La description est obligatoire.',
            'is_unique' => 'Cette étape existe déjà.',
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


}
