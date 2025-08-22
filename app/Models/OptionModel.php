<?php

namespace App\Models;

use CodeIgniter\Model;

class OptionModel extends Model
{
    protected $table            = 'option';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['key','value'];

    protected $validationRules = [
        'key'   => 'required|max_length[255]|is_unique[option.key,id,{id}]',
        'value' => 'permit_empty|string',
    ];

    protected $validationMessages = [
        'key' => [
            'required'   => 'La clé est obligatoire.',
            'max_length' => 'La clé ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cette clé existe déjà.',
        ],
        'value' => [
            'string' => 'La valeur doit être une chaîne de caractères.',
        ],
    ];

}
