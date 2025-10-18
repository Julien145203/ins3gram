<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Unit extends BaseController
{
    public function index()
    {
        helper(['form']);

        $unitModel = model('UnitModel');
        $totalUnits = $unitModel->countAllResults();

        return $this->view('admin/unit', [
            'totalUnits' => $totalUnits
        ]);
    }

    /**
     * Créer une unité
     */
    public function insert()
    {
        $unitModel = model('UnitModel');
        $data = $this->request->getPost();

        if ($unitModel->insert($data)) {
            $this->success('L’unité a bien été créée');
        } else {
            foreach ($unitModel->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('admin/unit');
    }

    /**
     * Mettre à jour une unité
     */
    public function update()
    {
        $unitModel = model('UnitModel');
        $data = $this->request->getPost();
        $id = $data['id'] ?? null;
        unset($data['id']);

        if ($id) {
            $unitModel->setValidationRule(
                'name',
                "required|max_length[255]|is_unique[unit.name,id,{$id}]"
            );
        }

        if ($unitModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "L’unité a été modifiée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $unitModel->errors()),
            ]);
        }
    }

    /**
     * Supprimer une unité
     */
    public function delete()
    {
        $unitModel = model('UnitModel');
        $id = $this->request->getPost('id');

        if ($unitModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "L’unité a été supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $unitModel->errors(),
            ]);
        }
    }

    /**
     * DataTables server-side
     */
    public function datatable()
    {
        $request = $this->request;

        if (!$request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Requête non autorisée');
        }

        $unitModel = model('UnitModel');

        $draw = (int)$request->getPost('draw');
        $start = (int)$request->getPost('start');
        $length = (int)$request->getPost('length');
        $search = $request->getPost('search')['value'] ?? '';
        $order = $request->getPost('order')[0] ?? null;
        $columns = $request->getPost('columns');

        $orderColumnName = $columns[$order['column']]['data'] ?? 'id';
        $orderDirection = $order['dir'] ?? 'ASC';

        $data = $unitModel->getPaginated($start, $length, $search, $orderColumnName, $orderDirection);
        $recordsTotal = $unitModel->getTotal();
        $recordsFiltered = $unitModel->getFiltered($search);

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Recherche pour Select2
     */
    public function search()
    {
        $request = $this->request;

        // Vérification AJAX
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        // Paramètres de recherche
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        $unitModel = model('UnitModel');
        $result = $unitModel->quickSearchForSelect2($search, $page, $limit);

        // Réponse JSON
        return $this->response->setJSON($result);
    }
}