<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IngredientModel;
use App\Models\BrandModel;
use App\Models\CategIngModel;
use App\Models\SubstituteModel;

class Ingredient extends BaseController
{
    protected $ingredientModel;
    protected $brandModel;
    protected $categModel;
    protected $substituteModel;

    public function __construct()
    {
        helper(['form','url']);
        $this->ingredientModel = new IngredientModel();
        $this->brandModel = new BrandModel();
        $this->categModel = new CategIngModel();
        $this->substituteModel = new SubstituteModel();
    }

    public function index()
    {
        return $this->view('admin/ingredient/index');
    }

    // Formulaire création
    public function new()
    {
        $data['brands'] = $this->brandModel->getOptionsForSelect();
        $data['categories'] = $this->categModel->getOptionsForSelect();
        $data['substitutes'] = [];
        $data['ingredientModel'] = $this->ingredientModel; // pour la vue
        return $this->view('admin/ingredient/form', $data);
    }

    // Insertion en BDD
    public function insert()
    {
        $post = $this->request->getPost();
        $file = $this->request->getFile('image');

        if ($this->ingredientModel->insert($post)) {
            $id = $this->ingredientModel->getInsertID();

            // Upload image
            if ($file && $file->isValid()) {
                $this->ingredientModel->uploadIngredientImage($file, $id);
            }

            // Substituts
            $subs = $post['substitutes'] ?? [];
            foreach ($subs as $sub) {
                if ($sub && $sub != $id) {
                    $this->substituteModel->insert([
                        'id_ingredient_base' => $id,
                        'id_ingredient_sub'  => $sub
                    ]);
                }
            }

            return redirect()->to('/admin/ingredient');
        }

        return redirect()->back()->withInput()->with('errors', $this->ingredientModel->errors());
    }

    // Formulaire édition
    public function edit($id)
    {
        $ingredient = $this->ingredientModel->find($id);
        if(!$ingredient) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $data['ingredient'] = $ingredient;
        $data['brands'] = $this->brandModel->getOptionsForSelect();
        $data['categories'] = $this->categModel->getOptionsForSelect();
        $data['ingredientModel'] = $this->ingredientModel; // pour la vue

        // Substitutes
        $data['substitutes'] = $this->substituteModel
            ->where('id_ingredient_base', $id)
            ->join('ingredient', 'substitute.id_ingredient_sub = ingredient.id')
            ->select('substitute.id_ingredient_sub, ingredient.name as substitute_name')
            ->findAll();

        // Reverse substitutes : ingrédients qui utilisent celui-ci comme substitut
        $data['usedIn'] = $this->substituteModel
            ->join('ingredient', 'substitute.id_ingredient_base = ingredient.id')
            ->where('substitute.id_ingredient_sub', $id)
            ->select('ingredient.id, ingredient.name')
            ->findAll();

        return $this->view('admin/ingredient/form', $data);
    }

    // Mise à jour
    public function update()
    {
        $post = $this->request->getPost();
        $id = $post['id'] ?? null;
        $file = $this->request->getFile('image');

        if ($id && $this->ingredientModel->update($id, $post)) {
            // Upload image
            if ($file && $file->isValid()) {
                $this->ingredientModel->uploadIngredientImage($file, $id);
            }

            // Supprimer les anciens substituts et ajouter les nouveaux
            $this->substituteModel->where('id_ingredient_base', $id)->delete();

            $subs = $post['substitutes'] ?? [];
            foreach ($subs as $sub) {
                if ($sub && $sub != $id) {
                    $this->substituteModel->insert([
                        'id_ingredient_base' => $id,
                        'id_ingredient_sub'  => $sub
                    ]);
                }
            }

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
            $this->substituteModel->where('id_ingredient_base',$id)->delete();
        }
        return redirect()->to('/admin/ingredient');
    }

    // Recherche AJAX pour Select2
    public function search()
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['error'=>'Requête non autorisée']);
        $search = $this->request->getGet('search') ?? '';
        $page   = (int)($this->request->getGet('page') ?? 1);
        $limit  = 20;

        $result = $this->ingredientModel->quickSearchForSelect2($search,$page,$limit);
        return $this->response->setJSON($result);
    }
}
