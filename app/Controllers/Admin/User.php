<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => '/admin/dashboard'],['text'=>"Utilisateurs", 'url' => '']];
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

        // Gestion de l'avatar
        $avatarFile = $this->request->getFile('avatar');

        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {

            $result = upload_file(
                $avatarFile,
                'avatars',
                $user->username,
                [
                    'entity_id' => $user->id,
                    'entity_type' => 'user',
                    'title' => 'Avatar de ' . $user->username,
                    'alt' => 'Photo de profil de ' . $user->username
                ]
            );

            if ($result instanceof \App\Entities\Media) {
                $this->success('Avatar mis à jour avec succès.');
            } else {
                $this->error($result['message']);
            }

        }

        //Sauvegarde s'il a une modification
        if ($user->hasChanged()) {
            if ($userModel->save($user)) {
                $this->success('Utilisateur mis à jour avec succès.');
            } else {
                $this->error('Erreur');
            }
        } else {
            $this->message('Aucune modification apportée aux infos de l\'utilisateur.');
        }
        return $this->redirect('/admin/user/' . $user->id);
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

    public function deleteAvatar() {
        $id = $this->request->getPost('id_user');
        $user = model('UserModel')->find($id);
        //Si il n'existe pas
        if(!$user){
            return $this->response->setJSON(
                [
                    'success' => false,
                    'message' => 'Utilisateur introuvable'
                ]
            );
        }

        if($user->deleteAvatar()){
            return $this->response->setJSON(
                [
                    'success' => true,
                    'message' => 'Avatar supprimé avec succès',
                ]
            );
        } else {
            return $this->response->setJSON(
                [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'avatar',
                ]
            );
        }
    }

    public function search()
    {
        $request = $this->request;

        // Vérification AJAX
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Requête non autorisée']);
        }

        $um = Model('UserModel');

        // Paramètres de recherche
        $search = $request->getGet('search') ?? '';
        $page = (int)($request->getGet('page') ?? 1);
        $limit = 20;

        // Utilisation de la méthode du Model (via le trait)
        $result = $um->quickSearchForSelect2($search, $page, $limit);

        // Réponse JSON
        return $this->response->setJSON($result);
    }
    public function delete()
    {
        $id = $this->request->getPost('id');
        $userModel = model('UserModel');

        // Récupérer l'utilisateur même s'il a un soft delete
        $user = $userModel->withDeleted()->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ]);
        }

        // Supprimer l'avatar si présent
        if ($user->hasAvatar()) { // suppose que tu as une méthode hasAvatar() ou check sur $user->image
            $user->deleteAvatar(); // utilise ta fonction existante dans l'entité pour supprimer le fichier
        }

        // Supprimer l'utilisateur définitivement
        if ($userModel->delete($id, true)) { // true pour hard delete
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Utilisateur et avatar supprimés avec succès'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ]);
        }
    }
}