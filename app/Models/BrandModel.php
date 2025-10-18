<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use CodeIgniter\Model;

class BrandModel extends Model
{
    use DataTableTrait, Select2Searchable;

    protected $table            = 'brand';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];
    protected $useTimestamps    = false;

    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[brand.name,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Le nom de la marque est obligatoire.',
            'max_length' => 'Le nom de la marque ne peut pas dépasser 255 caractères.',
            'is_unique'  => 'Cette marque existe déjà.',
        ],
    ];

    // ---------------- DataTable configuration ----------------
   protected function getDataTableConfig(): array
   {
       return [
           'searchable_fields' => ['brand.id', 'brand.name'], // recherche sur l'ID et le nom
           'joins' => [
               [
                   'table' => 'media',
                   'condition' => 'media.entity_id = brand.id AND media.entity_type = "brand"',
                   'type' => 'left',
               ],
           ],
           'select' => 'brand.*, media.file_path as image', // récupère le chemin de l'image
       ];
   }

    // ---------------- Select2 configuration ----------------
    protected $select2SearchFields     = ['name'];
    protected $select2DisplayField     = 'name';
    protected $select2AdditionalFields = [];

// -------------------- Sauvegarde marque (insert ou update) avec validation dynamique --------------------
    public function saveBrand(array $data, $id = null)
    {
        // Validation dynamique pour le name unique
        $rules = [
            'name' => 'required|max_length[255]',
        ];

        if ($id) {
            // Si on update, on ajoute la règle unique en excluant l'ID courant
            $rules['name'] .= "|is_unique[brand.name,id,{$id}]";
        } else {
            // Si création, unique standard
            $rules['name'] .= "|is_unique[brand.name]";
        }

        $this->setValidationRules($rules);

        // Retourne true ou false selon succès, accessible via $this->errors() si besoin
        if ($id) {
            return $this->update($id, $data);
        } else {
            return $this->insert($data);
        }
    }

}
