<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function index()
    {
        return $this->view('/admin/user/index');
    }

    public function edit($id_user) {
        $um = Model('UserModel');
        $user = $um->find($id_user);
        if (!$user) {
            $this->error('Utilisateur inexistant');
            return $this->redirect('/admin/user');
        }
        helper('form');
        $permissions = Model('UserPermissionModel')->findAll();
        return $this->view('/admin/user/form', ['user' => $user, 'permissions' => $permissions]);
    }

    public function create() {
        helper('form');
        $permissions = Model('UserPermissionModel')->findAll();

        return $this->view('/admin/user/form', ['permissions' => $permissions]);
    }

    public function update()
    {
        //Chargement de notre model
        $userModel = model('UserModel');
        // Récupérer les données depuis le formulaire
        $data = $this->request->getPost();
        // Récupérer l'ID depuis le formulaire
        $id = $this->request->getPost('id');

        // Charger l'utilisateur existant
        $user = $userModel->find($id);

        //Si je ne trouve pas d'utilisateur
        if (!$user) {
            $this->error('Utilisateur inexistant');
            return $this->redirect('/admin/user');
        }

        // Si mot de passe vide, on ne l'inclut pas
        if (empty($data['password'])) {
            //retire du tableau de données le password
            unset($data['password']);
        }

        // Remplir notre user avec les nouvelles données du formulaire
        $user->fill($data);

        // Sauvegarde (CodeIgniter fera un UPDATE car l'ID est présent)
        if ($userModel->save($user)) {
            $this->success('Utilisateur mis à jour avec succès.');
            return $this->redirect('/admin/user/' . $user->id);
        } else {
            $this->error('Erreur');
            return $this->redirect('/admin/user/' . $user->id);
        }
    }

    public function insert() {
        $userModel = model('UserModel');
        $data = $this->request->getPost();

        if (empty($data['password'])) {
            $this->error('Le mot de passe est obligatoire.');
            return $this->redirect('/admin/user/new');
        }

        // Créer manuellement une nouvelle instance de l'entité User
        $user = new \App\Entities\User();
        $user->fill($data);

        if ($userModel->save($user)) {
            $this->success('Utilisateur créé avec succès.');
            return $this->redirect('/admin/user/');
        } else {
            $this->error('Erreur lors de la création.');
            return $this->redirect('/admin/user/new');
        }
    }

    public function switchActive() {
        $id = $this->request->getPost('id_user');
        $userModel = model('UserModel');

        // Récupérer l'utilisateur (même s'il est soft deleted)
        $user = $userModel->withDeleted()->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ]);
        }

        if ($user->isActive()) {
            // Désactiver l'utilisateur (soft delete)
            $userModel->delete($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Utilisateur désactivé',
                'status' => 'inactive'
            ]);
        } else {
            // Réactiver avec la méthode restore
            if ($userModel->reactive($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Utilisateur activé',
                    'status' => 'active'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation'
                ]);
            }
        }
    }
}
