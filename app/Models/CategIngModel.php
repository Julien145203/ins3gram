<?php

namespace App\Models;

use CodeIgniter\Model;

class CategIngModel extends Model
{
    protected $table = 'categ_ing';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'id_categ_parent'];
    protected $useTimestamps = false;
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'id_categ_parent' => 'permit_empty|integer',
    ];

    // Vérifie que le nom est unique
    public function isUniqueName(string $name, ?int $id = null): bool
    {
        $builder = $this->builder()->where('name', $name);
        if ($id !== null) $builder->where('id !=', $id);
        return $builder->countAllResults() === 0;
    }

    // Retourne les catégories valides pour le parent
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

    // Récupère les données avec le parent pour DataTable
    public function getForDataTable()
    {
        $builder = $this->db->table($this->table . ' AS c');
        $builder->select('c.id, c.name, c.id_categ_parent, p.name AS parent_name');
        $builder->join('categ_ing AS p', 'c.id_categ_parent = p.id', 'left');
        return $builder->get()->getResultArray();
    }

    // Retourne un tableau clé => valeur pour les <select>
    public function getOptionsForSelect(): array
    {
        $categories = $this->orderBy('name', 'ASC')->findAll();
        $options = [];
        foreach ($categories as $c) {
            $options[$c['id']] = $c['name'];
        }
        return $options;
    }
}
