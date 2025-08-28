<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Entities\User;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        /** @var User|null $user */
        $user = $session->get('user');

        // Vérifier si l'utilisateur est connecté
        if (!$user instanceof User) {
            // Logger la tentative d'accès non autorisée
            log_message('info', 'Unauthorized access attempt to: ' . uri_string());

            // Flash message
            $session->setFlashdata('error', 'Vous devez être connecté pour accéder à cette page.');

            // Redirection
            return redirect()->to('/sign-in');
        }

        // Vérifier si l'utilisateur est toujours actif
        if (!$user->isActive()) {
            log_message('warning', "Inactive user {$user->id} tried to access: " . uri_string());
            $session->setFlashdata('error', 'Votre compte a été désactivé.');
        }

        // Vérifier la validité de la session (optionnel - sécurité renforcée)
        $lastActivity = $session->get('last_activity');
        $sessionTimeout = 3600; // 1 heure en secondes

        if ($lastActivity && (time() - $lastActivity) > $sessionTimeout) {
            log_message('info', "Session expired for user {$user->id}");
            session()->destroy();
            $session->setFlashdata('error', 'Votre session a expiré. Veuillez vous reconnecter.');
            return redirect()->to('/sign-in');
        }

        // Mettre à jour l'activité de la session
        $session->set('last_activity', time());

        // Si des rôles spécifiques sont requis
        if (!empty($arguments)) {
            $isAllowed = false;

            foreach ($arguments as $requiredRoleSlug) {
                if ($user->check($requiredRoleSlug)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                // Logger la tentative d'accès avec des permissions insuffisantes
                log_message('warning', "User {$user->id} with insufficient permissions tried to access: " . uri_string() . " (required: " . implode(', ', $arguments) . ")");

                $session->setFlashdata('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');

                // Rediriger vers une page d'erreur 403
                return redirect()->to('/forbidden');
            }
        }

        // Tout est OK, l'utilisateur peut continuer
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Ajouter des headers de sécurité (optionnel)
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}