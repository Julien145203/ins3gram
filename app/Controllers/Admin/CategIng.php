<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class CategIng extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => '/admin/dashboard'],['text'=>"Catégories", 'url' => '']];
    public function index()
    {
        helper(['form']);
        $model = model('CategIngModel');
        $categories = $model->orderBy('name')->findAll();
        return $this->view('admin/categ-ing', ['categories' => $categories]);
    }

    public function insert()
    {
        $model = model('CategIngModel');
        $data = $this->request->getPost();

        if (empty($data['id_categ_parent'])) $data['id_categ_parent'] = null;

        if ($model->insert($data)) {
            $this->success('Catégorie d\'ingrédients bien créée');
        } else {
            foreach ($model->errors() as $error) $this->error($error);
        }

        return $this->redirect('admin/category-ingredient');
    }

    public function update()
    {
        $model = model('CategIngModel');
        $data = $this->request->getPost();
        $id = $data['id'] ?? null;
        unset($data['id']);

        if (empty($data['id_categ_parent'])) $data['id_categ_parent'] = null;

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
                'message' => 'Catégorie mise à jour avec succès.',
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
                'message' => 'Catégorie supprimée avec succès.',
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
        $categories = $model->getValidParents($id);
        return $this->response->setJSON($categories);
    }

    // Méthode pour DataTable
    public function datatable()
    {
        $model = model('CategIngModel');
        $data = $model->getForDataTable();
        return $this->response->setJSON(['data' => $data]);
    }
}
