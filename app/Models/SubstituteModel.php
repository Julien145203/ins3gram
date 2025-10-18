<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Traits\DataTableTrait;

class SubstituteModel extends Model
{
    use DataTableTrait;
    protected $table = 'substitute';
    protected $primaryKey = 'id_ingredient_base'; // clé composite
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_ingredient_base','id_ingredient_sub'];
    protected $validationRules = [
        'id_ingredient_base' => 'required|integer',
        'id_ingredient_sub'  => 'required|integer|differs[id_ingredient_base]',
    ];

    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['ingredient.name', 'substitute_ingredient.name'],
            'joins' => [
                [
                    'table' => 'ingredient as ingredient',
                    'condition' => 'substitute.id_ingredient_base = ingredient.id',
                    'type' => 'inner'
                ],
                [
                    'table' => 'ingredient as substitute_ingredient',
                    'condition' => 'substitute.id_ingredient_sub = substitute_ingredient.id',
                    'type' => 'inner'
                ]
            ],
            'select' => 'substitute.*, ingredient.name as ingredient_name, substitute_ingredient.name as substitute_name',
            'with_deleted' => false,
        ];
    }

    // ---------------- Ingrédients utilisant ce substitut ----------------
    public function getUsedBy(int $subId): array
    {
        return $this->select('ingredient.id, ingredient.name')
            ->join('ingredient', 'substitute.id_ingredient_base = ingredient.id')
            ->where('substitute.id_ingredient_sub', $subId)
            ->findAll();
    }
}
