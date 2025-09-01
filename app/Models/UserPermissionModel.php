<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Traits\DataTableTrait;

class UserPermissionModel extends Model
{
    use DataTableTrait;
    protected $table            = 'user_permission';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','slug'];
    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[user_permission.name,id,{id}]',
        'slug' => 'max_length[255]|is_unique[user_permission.name,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Le nom de la permission est obligatoire.',
            'max_length' => 'Le nom de la permission ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cette permission existe déjà.',
        ],
        'slug' => [
            'max_length' => 'Le slug de la permission ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Ce slug existe déjà.',
        ],
    ];

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name'])) {
            helper('text');
            $slug = url_title(convert_accented_characters($data['data']['name']), '-', true);
            $data['data']['slug'] = $slug;
        }
        return $data;
    }
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'name',
                'id',
            ],
            'joins' => [],
            'select' => '*',
        ];
    }
}