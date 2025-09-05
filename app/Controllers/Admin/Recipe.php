<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Recipe extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => '/admin/dashboard']];

    public function index()
    {
        $this->addBreadcrumb('Recettes', "");
        return $this->view('admin/recipe/index');
    }

    public function create()
    {
        helper('form');
        $this->addBreadcrumb('Recettes', "/admin/recipe");
        $this->addBreadcrumb('Création d\'une recette', "");
        $users = model('UserModel')->findAll();
        return $this->view('admin/recipe/form', ['users' => $users]);
    }

    public function edit($id_recipe)
    {
        helper('form');
        $this->addBreadcrumb('Recettes', "/admin/recipe");
        $this->addBreadcrumb('Modification d\'une recette', "");
        $recipe = model('RecipeModel')->withDeleted()->find($id_recipe);
        if (!$recipe) {
            $this->error('Recette introuvable');
            return $this->redirect('/admin/recipe');
        }
        $users = model('UserModel')->findAll();
        return $this->view('admin/recipe/form', ['users' => $users, 'recipe' => $recipe]);
    }

    public function insert()
    {
        $data = $this->request->getPost();
        $rm = model('RecipeModel');
        if ($rm->insert($data)) {
            $this->success('Recette créée avec succès !');
        } else {
            foreach ($rm->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }

    public function update()
    {
        $data = $this->request->getPost();
        $id_recipe = $data['id_recipe'];
        $rm = model('RecipeModel');
        if ($rm->update($id_recipe, $data)) {
            $this->success('Recette modifiée avec succès !');
        } else {
            foreach ($rm->errors() as $error) {
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }

    /**
     * Retourne toutes les recettes au format JSON pour DataTables
     */
    public function list()
    {
        $rm = model('RecipeModel');
        $recipes = $rm->select('recipe.*, user.username')
            ->join('user', 'user.id = recipe.id_user', 'left')
            ->withDeleted() // Inclure les soft-deleted
            ->orderBy('recipe.id', 'DESC')
            ->findAll();

        $data = [];
        foreach ($recipes as $recipe) {
            $data[] = [
                'id' => $recipe['id'],
                'name' => $recipe['name'],
                'username' => $recipe['username'],
                'updated_at' => $recipe['updated_at'],
                'alcool' => $recipe['alcool'],
                'deleted_at' => $recipe['deleted_at']
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Supprimer une recette via soft-delete
     */
    public function delete()
    {
        $id = $this->request->getPost('id');
        $rm = model('RecipeModel');

        if ($rm->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Recette supprimée avec succès !",
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => $rm->errors(),
            ]);
        }
    }
}