<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DataTable extends BaseController
{
    public function searchdatatable()
    {
        // Validation basique
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Only AJAX requests allowed']);
        }

        $model_name = $this->request->getPost('model');

        // Vérification de sécurité sur le nom du modèle
        if (empty($model_name) || !preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $model_name)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid model name']);
        }

        try {
            $model = model($model_name);

            // Vérifier que le modèle a les méthodes nécessaires (utilise le trait)
            if (!method_exists($model, 'getPaginated')) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Model does not support DataTable']);
            }

            // Paramètres de pagination et de recherche envoyés par DataTables
            $draw        = (int) $this->request->getPost('draw');
            $start       = (int) $this->request->getPost('start');
            $length      = (int) $this->request->getPost('length');
            $searchValue = $this->request->getPost('search')['value'] ?? '';

            // Informations sur le tri envoyées par DataTables
            $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
            $orderDirection = $this->request->getPost('order')[0]['dir'] ?? 'asc';
            $orderColumnName = $this->request->getPost('columns')[$orderColumnIndex]['data'] ?? 'id';

            // Obtenez les données triées et filtrées
            $data = $model->getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection);

            // Obtenez le nombre total de lignes sans filtre
            $totalRecords = $model->getTotal();

            // Obtenez le nombre total de lignes filtrées pour la recherche
            $filteredRecords = $model->getFiltered($searchValue);

            $result = [
                'draw'            => $draw,
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $data,
            ];

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'DataTable Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }
}