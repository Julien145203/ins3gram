<?php

namespace App\Traits;

trait Select2Searchable
{
    /**
     * Recherche générique pour Select2 avec pagination
     *
     * @param string $search Terme de recherche
     * @param int $page Numéro de page (commence à 1)
     * @param int $limit Nombre d'éléments par page
     * @param array $searchFields Champs dans lesquels rechercher (par défaut: ['name'])
     * @param string $displayField Champ à afficher comme texte (par défaut: 'name')
     * @param array $additionalFields Champs supplémentaires à retourner
     * @param array $conditions Conditions WHERE supplémentaires
     * @param string $orderBy Champ de tri (par défaut: 'name')
     * @param string $orderDirection Direction du tri (par défaut: 'ASC')
     * @return array Format compatible Select2
     */
    public function searchForSelect2(
        string $search = '',
        int $page = 1,
        int $limit = 20,
        array $searchFields = ['name'],
        string $displayField = 'name',
        array $additionalFields = [],
        array $conditions = [],
        string $orderBy = 'name',
        string $orderDirection = 'ASC'
    ): array {
        $offset = ($page - 1) * $limit;

        // Construction de la requête de base
        $builder = $this->builder();

        // Application des conditions supplémentaires
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $builder->whereIn($field, $value);
            } else {
                $builder->where($field, $value);
            }
        }

        // Ajout des conditions de recherche si un terme est fourni
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($searchFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($field, $search);
                } else {
                    $builder->orLike($field, $search);
                }
            }
            $builder->groupEnd();
        }

        // Comptage total pour la pagination (sans affecter la requête principale)
        $totalCount = $builder->countAllResults(false);

        // Récupération des résultats avec limite et tri
        $results = $builder->orderBy($orderBy, $orderDirection)
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        // Formatage des données pour Select2
        $formattedResults = [];
        foreach ($results as $result) {
            $item = [
                'id' => $result[$this->primaryKey],
                'text' => $result[$displayField] ?? ''
            ];

            // Ajout des champs supplémentaires
            foreach ($additionalFields as $field) {
                if (isset($result[$field])) {
                    $item[$field] = $result[$field];
                }
            }

            $formattedResults[] = $item;
        }

        // Retour du format attendu par Select2
        return [
            'results' => $formattedResults,
            'pagination' => [
                'more' => ($offset + $limit) < $totalCount
            ],
            'total' => $totalCount
        ];
    }

    /**
     * Recherche rapide pour Select2 avec configuration par défaut du modèle
     *
     * @param string $search
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function quickSearchForSelect2(string $search = '', int $page = 1, int $limit = 20,$orderBy = 'id', $orderDirection = 'ASC'): array
    {
        // Configuration par défaut, peut être surchargée dans chaque modèle
        $searchFields = property_exists($this, 'select2SearchFields')
            ? $this->select2SearchFields
            : ['name'];

        $displayField = property_exists($this, 'select2DisplayField')
            ? $this->select2DisplayField
            : 'name';

        $additionalFields = property_exists($this, 'select2AdditionalFields')
            ? $this->select2AdditionalFields
            : [];

        $conditions = property_exists($this, 'select2DefaultConditions')
            ? $this->select2DefaultConditions
            : [];

        return $this->searchForSelect2(
            $search,
            $page,
            $limit,
            $searchFields,
            $displayField,
            $additionalFields,
            $conditions,
            $orderBy,
            $orderDirection
        );
    }

    /**
     * Recherche avec filtre de statut actif
     *
     * @param string $search
     * @param int $page
     * @param int $limit
     * @param string $activeField Nom du champ de statut (par défaut: 'active')
     * @return array
     */
    public function searchActiveForSelect2(
        string $search = '',
        int $page = 1,
        int $limit = 20,
        string $activeField = 'active'
    ): array {
        return $this->searchForSelect2(
            $search,
            $page,
            $limit,
            property_exists($this, 'select2SearchFields') ? $this->select2SearchFields : ['name'],
            property_exists($this, 'select2DisplayField') ? $this->select2DisplayField : 'name',
            property_exists($this, 'select2AdditionalFields') ? $this->select2AdditionalFields : [],
            [$activeField => 1]
        );
    }

    /**
     * Récupère les options pour un select statique
     *
     * @param array $conditions
     * @param string $orderBy
     * @param string $orderDirection
     * @return array
     */
    public function getOptionsForSelect(
        array $conditions = [],
        string $orderBy = 'name',
        string $orderDirection = 'ASC'
    ): array {
        $builder = $this->builder();

        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $builder->whereIn($field, $value);
            } else {
                $builder->where($field, $value);
            }
        }

        $results = $builder->orderBy($orderBy, $orderDirection)
            ->get()
            ->getResultArray();

        $options = [];
        $displayField = property_exists($this, 'select2DisplayField')
            ? $this->select2DisplayField
            : 'name';

        foreach ($results as $result) {
            $options[$result[$this->primaryKey]] = $result[$displayField];
        }

        return $options;
    }
}