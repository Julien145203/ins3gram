<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Recipe extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord','url' => '/admin/dashboard']];
    public function index()
    {
        $this->addBreadcrumb('Recettes', "");
        return $this->view('admin/recipe/index');
    }

    public function create() {
        helper('form');
        $this->addBreadcrumb('Recettes', "/admin/recipe");
        $this->addBreadcrumb('Création d\'une recette', "");
        $users = Model('UserModel')->findAll();
        return $this->view('admin/recipe/form', ['users' => $users]);
    }
    public function edit($id_recipe) {
        helper('form');
        $this->addBreadcrumb('Recettes', "/admin/recipe");
        $this->addBreadcrumb('Modification d\'une recette', "");
        $recipe = Model('RecipeModel')->find($id_recipe);
        $qm = Model('QuantityModel');
        $ingredients = $qm->getQuantityByRecipe($id_recipe);
        if(!$recipe) {
            $this->error('Recette introuvable');
            return $this->redirect('/admin/recipe');
        }
        $users = Model('UserModel')->findAll();
        return $this->view('admin/recipe/form', ['users' => $users,'recipe' => $recipe,'ingredients' => $ingredients]);
    }

    public function insert() {
        $data = $this->request->getPost();

        $rm = Model('RecipeModel');

        //Ajout de ma recette
        if($rm->insert($data)){
            $this->success('Recette créée avec succès !');

            //Récupération de l'ID de ma recette ajouté
            $id_recipe = $rm->getInsertID();
            $qm = Model('QuantityModel');

            //Ajout des ingrédients
            foreach($data['ingredients'] as $ingredient) {
                $ingredient['id_recipe'] = $id_recipe;
                if ($qm->insert($ingredient)) {
                    $this->success('Ingrédient ajouté avec succès !');
                } else {
                    foreach($qm->errors() as $error){
                        $this->error($error);
                    }
                }
            }
        } else {
            foreach($rm->errors() as $error){
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }

    public function update() {
        $data = $this->request->getPost();
        $id_recipe = $data['id_recipe'];
        $rm = Model('RecipeModel');
        if($rm->update($id_recipe,$data)){
            $this->success('Recette modifiée avec succès !');
        } else {
            foreach($rm->errors() as $error){
                $this->error($error);
            }
        }
        return $this->redirect('/admin/recipe');
    }
}