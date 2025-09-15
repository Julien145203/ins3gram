<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Brand extends BaseController
{
    public function index()
    {
        helper('form');
        return $this->view('admin/brand');
    }

    public function insert()
    {
        $brandModel = model('BrandModel');
        $data = $this->request->getPost();

        if ($brandModel->insert($data)) {
            $this->success('La marque a bien été créée');
        } else {
            foreach ($brandModel->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('admin/brand');
    }

    public function update()
    {
        $brandModel = model('BrandModel');
        $data = $this->request->getPost();
        $id = $data['id'] ?? null;
        unset($data['id']);

        if ($brandModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La marque a été modifiée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $brandModel->errors(),
            ]);
        }
    }

    public function delete()
    {
        $brandModel = model('BrandModel');
        $id = $this->request->getPost('id');

        if ($brandModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La marque a été supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $brandModel->errors(),
            ]);
        }
    }

    /**
     * Recherche pour Select2
     */
    public function search()
    {
        $request = $this->request;

        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $brandModel = model('BrandModel');
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        $result = $brandModel->quickSearchForSelect2($search, $page, $limit);

        return $this->response->setJSON($result);
    }
}
