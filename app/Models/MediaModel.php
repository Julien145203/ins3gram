<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class MediaModel extends Model
{
    use DataTableTrait;
    protected $table            = 'media';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['file_path','entity_id', 'entity_type','title','alt'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'file_path'   => 'required|string|is_unique[media.file_path,id,{id}]',
        'entity_id'   => 'required|integer',
        'entity_type' => 'required|in_list[user,recipe,step,ingredient,brand]',
        'title'       => 'permit_empty|max_length[255]',
        'alt'         => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'file_path' => [
            'required'  => 'Le chemin du fichier est obligatoire.',
            'is_unique' => 'Ce fichier existe déjà.',
        ],
        'entity_id' => [
            'required' => 'L’ID de l’entité est obligatoire.',
            'integer'  => 'L’ID de l’entité doit être un nombre.',
        ],
        'entity_type' => [
            'required' => 'Le type d’entité est obligatoire.',
            'in_list'  => 'Le type d’entité doit être parmi : user, recipe, step, ingredient ou brand.',
        ],
        'title' => [
            'max_length' => 'Le titre ne peut pas dépasser 255 caractères.',
        ],
        'alt' => [
            'max_length' => 'Le texte alternatif ne peut pas dépasser 255 caractères.',
        ],
    ];
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['media.type', 'media.url'],
            'joins' => [
                [
                    'table' => 'recipe',
                    'condition' => 'media.recipe_id = recipe.id',
                    'type' => 'inner'
                ]
            ],
            'select' => 'media.*, recipe.title as recipe_title',
            'with_deleted' => false,
        ];
    }
}
