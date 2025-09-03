<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Unit extends BaseController
{
    public function index()
    {
        helper('form');
        return $this->view('admin/unit');
    }

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

    public function update()
    {
        $unitModel = model('UnitModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);

        if ($unitModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "L’unité a été modifiée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $unitModel->errors(),
            ]);
        }
    }

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
}
