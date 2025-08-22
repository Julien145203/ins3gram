<?php

namespace App\Models;

use CodeIgniter\Model;

class OpinionModel extends Model
{
    protected $table            = 'opinion';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['comments','score','id_recipe','id_user'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'comments'  => 'permit_empty|string',
        'score'     => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'id_recipe' => 'required|integer',
        'id_user'   => 'required|integer',
    ];

    protected $validationMessages = [
        'comments' => [
            'string' => 'Le commentaire doit être une chaîne de caractères.',
        ],
        'score' => [
            'required'              => 'La note est obligatoire.',
            'integer'               => 'La note doit être un nombre.',
            'greater_than_equal_to' => 'La note doit être au minimum 1.',
            'less_than_equal_to'    => 'La note doit être au maximum 5.',
        ],
        'id_recipe' => [
            'required' => 'La recette est obligatoire.',
            'integer'  => 'L’ID de la recette doit être un nombre.',
        ],
        'id_user' => [
            'required' => 'L’utilisateur est obligatoire.',
            'integer'  => 'L’ID de l’utilisateur doit être un nombre.',
        ],
    ];


}
