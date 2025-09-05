<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class CategIngModel extends Model
{
    use DataTableTrait;
    protected $table            = 'categ_ing';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','id_categ_parent'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'name'          => 'required|max_length[255]|is_unique[categ_ing.name,id,{id}]',
        'id_categ_parent'=> 'permit_empty|integer',
    ];
    protected $validationMessages = [
        'name' => [
            'required'   => 'Le nom de la catégorie est obligatoire.',
            'max_length' => 'Le nom de la catégorie ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cette catégorie existe déjà.',
        ],
        'id_categ_parent' => [
            'integer' => 'L’ID du parent doit être un nombre.',
        ],
    ];

    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'categ_ing.name',
                'categ_ing.id',
                'parent_categ.name',
            ],
            'joins' => [
                [
                    'table' => 'categ_ing as parent_categ',
                    'condition' => 'parent_categ.id = categ_ing.id_categ_parent',
                    'type' => 'left',
                ]
            ],
            'select' => 'categ_ing.*, parent_categ.name as parent_name',
        ];
    }
}