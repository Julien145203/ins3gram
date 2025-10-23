<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Tag extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => '/admin/dashboard'],['text'=>"Tag", 'url' => '']];
    public function index()
    {
        helper('form'); // Charge le helper form pour form_open()
        return $this->view('admin/tag');
    }

    public function insert()
    {
        $model = model('TagModel');
        $data = $this->request->getPost();

        if ($model->insert($data)) {
            $this->success('Le tag a bien été créé');
        } else {
            foreach ($model->errors() as $error) {
                $this->error($error);
            }
        }

        return $this->redirect('admin/tag');
    }

    public function update()
    {
        $model = model('TagModel');
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);

        if ($model->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Le tag a été modifié avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $model->errors(),
            ]);
        }
    }

    public function delete()
    {
        $model = model('TagModel');
        $id = $this->request->getPost('id');

        if ($model->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Le tag a été supprimé avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $model->errors(),
            ]);
        }
    }
}
