<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table            = 'tag';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];

    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[tag.name,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Le nom du tag est obligatoire.',
            'max_length' => 'Le nom du tag ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Ce tag existe déjà.',
        ],
    ];

}
