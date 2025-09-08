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
        'name'          => 'required|max_length[255]',
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
            'searchable_fields' => ['name','id','id_categ_parent'],
            'joins' => [],
            'select' => '*',
        ];
    }
    public function isUniqueName(string $name, ?int $id = null): bool
    {
        $builder = $this->builder()->where('name', $name);
        if ($id !== null) {
            $builder->where('id !=', $id);
        }
        return $builder->countAllResults() === 0;
    }
    public function getValidParents($excludeId = null)
    {
        $builder = $this->builder()->select('id, name, id_categ_parent');

        if ($excludeId) {
            $children = $this->where('id_categ_parent', $excludeId)->findColumn('id') ?? [];
            $exclude = array_merge([$excludeId], $children);
            $builder->whereNotIn('id', $exclude);
        }

        return $builder->get()->getResultArray();
    }
}
