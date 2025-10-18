<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use CodeIgniter\Model;

class IngredientModel extends Model
{
    use DataTableTrait, Select2Searchable;
    protected $table            = 'ingredient';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','description','id_brand','id_categ','image'];

    protected $validationRules = [
        'id' => 'permit_empty|integer',
        'name' => 'required|max_length[255]|is_unique[ingredient.name,id,{id}]',
        'description' => 'permit_empty|string',
        'id_categ' => 'permit_empty|integer',
        'id_brand' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom de l’ingrédient est obligatoire.',
            'max_length' => 'Le nom de l’ingrédient ne peut pas dépasser 255 caractères.',
            'is_unique' => 'Cet ingrédient existe déjà.',
        ],
    ];

    // ---------------- Select2 configuration ----------------
    protected $select2SearchFields = ['name', 'description'];
    protected $select2DisplayField = 'name';
    protected $select2AdditionalFields = ['description','id_brand','id_categ'];

    // ---------------- DataTable configuration ----------------
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'ingredient.name',
                'ingredient.description',
                'brand.name',
                'categ_ing.name'
            ],
            'joins' => [
                [
                    'table' => 'brand',
                    'condition' => 'ingredient.id_brand = brand.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'categ_ing',
                    'condition' => 'ingredient.id_categ = categ_ing.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'media',
                    'condition' => "media.entity_type = 'ingredient' AND media.entity_id = ingredient.id",
                    'type' => 'left'
                ]
            ],
            'select' => 'ingredient.*, brand.name as brand_name, categ_ing.name as categ_name, media.file_path as image',
            'with_deleted' => false
        ];
    }

    // ---------------- Gestion image ----------------
    public function uploadIngredientImage(\CodeIgniter\Files\File $file, int $ingredientId): string|array|bool
    {
        helper('upload');

        $mediaData = [
            'entity_id' => $ingredientId,
            'entity_type' => 'ingredient',
            'type' => 'image'
        ];

        return upload_file($file, 'ingredients', null, $mediaData, false);
    }

    // ---------------- Récupération image ----------------
    public function getImage(int $ingredientId)
    {
        return $this->db->table('media')
            ->where('entity_type','ingredient')
            ->where('entity_id',$ingredientId)
            ->orderBy('id','DESC')
            ->get()
            ->getRowArray()['file_path'] ?? null;
    }
}
