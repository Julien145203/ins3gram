<?php

namespace App\Traits;

trait DataTableTrait
{
    /**
     * Configuration pour DataTable - à surcharger dans chaque modèle
     */
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['id'], // Champs par défaut
            'joins' => [],
            'select' => '*',
            'with_deleted' => false, // Inclure les enregistrements soft deleted
        ];
    }

    /**
     * Applique les jointures configurées
     */
    protected function applyJoins($builder, array $joins = null): void
    {
        $joins = $joins ?? $this->getDataTableConfig()['joins'];

        foreach ($joins as $join) {
            $builder->join(
                $join['table'],
                $join['condition'],
                $join['type'] ?? 'left'
            );
        }
    }

    /**
     * Applique les conditions de recherche
     */
    protected function applySearch($builder, string $searchValue, array $searchableFields = null): void
    {
        if (empty($searchValue)) {
            return;
        }

        $searchableFields = $searchableFields ?? $this->getDataTableConfig()['searchable_fields'];

        $builder->groupStart();
        foreach ($searchableFields as $index => $field) {
            if ($index === 0) {
                $builder->like($field, $searchValue);
            } else {
                $builder->orLike($field, $searchValue);
            }
        }
        $builder->groupEnd();
    }

    /**
     * Prépare le builder avec la configuration
     */
    protected function prepareBuilder(): \CodeIgniter\Database\BaseBuilder
    {
        $config = $this->getDataTableConfig();

        // Si with_deleted est activé, utiliser withDeleted()
        if (!empty($config['with_deleted']) && method_exists($this, 'withDeleted')) {
            $builder = $this->withDeleted()->builder();
        } else {
            $builder = $this->builder();
        }

        // Applique les jointures
        $this->applyJoins($builder, $config['joins']);

        // Applique la sélection
        if (!empty($config['select'])) {
            $builder->select($config['select']);
        }

        return $builder;
    }

    public function getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection)
    {
        $config = $this->getDataTableConfig();
        $builder = $this->prepareBuilder();

        // Applique la recherche
        $this->applySearch($builder, $searchValue, $config['searchable_fields']);

        // Applique le tri
        if ($orderColumnName && $orderDirection) {
            $builder->orderBy($orderColumnName, $orderDirection);
        }

        $builder->limit($length, $start);

        return $builder->get()->getResultArray();
    }

    public function getTotal()
    {
        $config = $this->getDataTableConfig();

        // Pour le total, on utilise le builder de base avec withDeleted si configuré
        if (!empty($config['with_deleted']) && method_exists($this, 'withDeleted')) {
            $builder = $this->withDeleted()->builder();
        } else {
            $builder = $this->builder();
        }

        $this->applyJoins($builder, $config['joins']);

        return $builder->countAllResults();
    }

    public function getFiltered($searchValue)
    {
        $config = $this->getDataTableConfig();
        $builder = $this->prepareBuilder();

        // Applique la recherche
        $this->applySearch($builder, $searchValue, $config['searchable_fields']);

        return $builder->countAllResults();
    }
}