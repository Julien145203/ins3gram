<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function signIn()
    {
        // Si déjà connecté, rediriger
        if (session()->get('user')) {
            return redirect()->to('/admin/dashboard');
        }
        helper('form');
        return $this->view('front/auth/sign_in', [], false);
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validation basique
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email et mot de passe requis');
        }

        // Rechercher l'utilisateur
        $userModel = model('UserModel');
        $user = $userModel->findByEmail($email);

        if (!$user || !$user->verifyPassword($password)) {
            return redirect()->back()->with('error', 'Identifiants incorrects');
        }

        if (!$user->isActive()) {
            return redirect()->back()->with('error', 'Compte désactivé');
        }

        // Connexion réussie
        $session = session();
        $session->set([
            'user' => $user,
            'user_id' => $user->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ]);

        log_message('info', "User {$user->id} logged in successfully");

        // Rediriger selon le rôle
        if ($user->isAdmin()) {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/');
    }

    public function logout()
    {
        $userId = session()->get('user_id');

        session()->destroy();

        log_message('info', "User {$userId} logged out");

        return redirect()->to('/sign-in')->with('success', 'Déconnexion réussie');
    }
}
