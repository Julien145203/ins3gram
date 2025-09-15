<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IngredientModel;
use App\Models\BrandModel;
use App\Models\CategIngModel;

class Ingredient extends BaseController
{
    protected $ingredientModel;
    protected $brandModel;
    protected $categModel;

    public function __construct()
    {
        helper('form');
        $this->ingredientModel = new IngredientModel();
        $this->brandModel = new BrandModel();
        $this->categModel = new CategIngModel();
    }

    // Liste
    public function index()
    {
        $data['ingredients'] = $this->ingredientModel->getPaginated(0, 100, '', 'id', 'ASC');
        return $this->view('admin/ingredient/index', $data);
    }

    // Formulaire création
    public function new()
    {
        $data['brands'] = $this->brandModel->getOptionsForSelect();
        $data['categories'] = $this->categModel->getOptionsForSelect();

        return $this->view('admin/ingredient/form', $data);
    }

    // Insertion en BDD
    public function insert()
    {
        $post = $this->request->getPost();
        if ($this->ingredientModel->insert($post)) {
            return redirect()->to('/admin/ingredient');
        }

        return redirect()->back()->withInput()->with('errors', $this->ingredientModel->errors());
    }

    // Formulaire édition
    public function edit($id)
    {
        $ingredient = $this->ingredientModel->find($id);
        if (!$ingredient) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Ingrédient #$id introuvable");
        }

        $data['ingredient'] = $ingredient;
        $data['brands'] = $this->brandModel->getOptionsForSelect();
        $data['categories'] = $this->categModel->getOptionsForSelect();

        return $this->view('admin/ingredient/form', $data);
    }

    // Mise à jour
    public function update()
    {
        $post = $this->request->getPost();
        $id = $post['id'] ?? null;

        if ($id && $this->ingredientModel->update($id, $post)) {
            return redirect()->to('/admin/ingredient');
        }

        return redirect()->back()->withInput()->with('errors', $this->ingredientModel->errors());
    }

    // Suppression
    public function delete()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            $this->ingredientModel->delete($id);
        }
        return redirect()->to('/admin/ingredient');
    }

    // Recherche AJAX pour Select2
    public function search()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $search = $this->request->getGet('search') ?? '';
        $page   = (int)($this->request->getGet('page') ?? 1);
        $limit  = 20;

        $result = $this->ingredientModel->quickSearchForSelect2($search, $page, $limit);

        return $this->response->setJSON($result);
    }
}
