<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserFavorites extends BaseController
{
    protected $start_session = true;

    /**
     * Affiche la page des favoris de l'utilisateur connecté
     */
    public function index()
    {
        if (!$this->session->has('user')) {
            $this->error('Vous devez être connecté pour accéder à vos favoris.');
            return $this->redirect('/sign-in');
        }

        $userId = $this->session->get('user')->id;

        $favorites = model('FavoriteModel')->getUserFavorites($userId);

        $this->title = 'Mes Favoris';

        return $this->view('/front/favorites/index', [
            'favorites' => $favorites,
            'totalFavorites' => count($favorites)
        ], false);
    }


    /**
     * Ajouter ou retirer un favori (AJAX)
     */
    public function toggle()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        if (!$this->session->has('user')) {
            return $this->response->setJSON([
                'success' => false,
                'redirect' => '/sign-in'
            ]);
        }

        $recipeId = (int) $this->request->getPost('id_recipe');
        $userId   = $this->session->get('user')->id;

        $recipe = model('RecipeModel')
            ->where('deleted_at', null)
            ->find($recipeId);

        if (!$recipe) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Recette introuvable'
            ]);
        }

        $result = model('FavoriteModel')->switchFavorite($recipeId, $userId);

        return $this->response->setJSON([
            'success' => true,
            'type' => $result['type']
        ]);
    }


    /**
     * Récupérer le nombre de favoris de l'utilisateur (AJAX)
     */
    public function count()
    {
        if (!$this->session->has('user')) {
            return $this->response->setJSON(['count' => 0]);
        }

        $userId = $this->session->get('user')->id;

        // Utiliser le model
        $count = model('FavoriteModel')->countByUser($userId);

        return $this->response->setJSON(['count' => $count]);
    }

}