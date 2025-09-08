<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CategIng extends BaseController
{
    public function index()
    {
        helper('form');
        $model = model('CategIngModel');
        $categories = $model->findAll(); // pour le select des parents
        return $this->view('admin/categ-ing', ['categories' => $categories]);
    }

    public function insert()
    {
        $model = model('CategIngModel');
        $data = $this->request->getPost();
        if (empty($data['id_categ_parent'])) {
            $data['id_categ_parent'] = null;
        }
        if ($model->insert($data)) {
            $this->success('La catégorie a bien été créée');
        } else {
            foreach ($model->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('admin/categ-ing');
    }

    public function update()
    {
        $model = model('CategIngModel');
        $data = $this->request->getPost();
        $id   = $data['id'];
        unset($data['id']);
        if (empty($data['id_categ_parent'])) {
            $data['id_categ_parent'] = null;
        }
        if (!empty($data['id_categ_parent']) && $data['id_categ_parent'] == $id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une catégorie ne peut pas être son propre parent.',
            ]);
        }
        if (!$model->isUniqueName($data['name'], $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ce nom de catégorie existe déjà.',
            ]);
        }
        if ($model->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La catégorie a été modifiée avec succès !",
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
        $model = model('CategIngModel');
        $id = $this->request->getPost('id');
        if ($model->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "La catégorie a été supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $model->errors(),
            ]);
        }
    }
    public function getValidParents()
    {
        $id = $this->request->getGet('id');
        $model = model('CategIngModel');
        $categories = $model->findAll();
        $valid = array_filter($categories, function($c) use ($id) {
            return $c['id'] != $id && empty($c['id_categ_parent']);
        });
        return $this->response->setJSON(array_values($valid));
    }
}
