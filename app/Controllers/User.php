<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function register() {
        helper(['form']);
        return $this->view('/front/user/register', [], false);
    }

    public function insert() {
        $userModel = model('UserModel');
        $data = $this->request->getPost();

        if (empty($data['password'])) {
            //$this->error('Le mot de passe est obligatoire.');
            return $this->redirect('/register');
        }

        // Créer manuellement une nouvelle instance de l'entité User
        $user = new \App\Entities\User();
        $user->fill($data);

        if ($userModel->save($user)) {
            //$this->success('Utilisateur créé avec succès.');
            return $this->redirect('/');
        } else {
            //$this->error('Erreur lors de la création.');
            return $this->redirect('/register');
        }
    }
}