<?php
namespace Tests\Feature\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

final class AuthControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = 'App';
    protected $seed = 'App\Database\Seeds\MasterSeeder';
    public function testHomePageLoads()
    {
        $result = $this->get('/');

        $result->assertStatus(200);
        $result->assertSee('ins3gram');
        $result->assertSeeElement('.row');
    }

    public function testLoginPageIsAccessible()
    {
        $result = $this->get('/sign-in');

        $result->assertStatus(200);
        $result->assertSee('Connexion');
    }

    public function testLoginWithValidCredentialsAsUser()
    {
        // Créer un utilisateur de test (NON admin)
        $user = new \App\Entities\User([
            'email' => 'user@example.com',
            'username' => 'testuser',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,  // User normal
        ]);
        $user->setPassword('password123');
        model('UserModel')->save($user);

        // Tenter la connexion
        $result = $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123'
        ]);

        // Vérifications
        $result->assertRedirectTo('/');
        $result->assertSessionHas('user_id');
        $result->assertSessionHas('is_logged_in', true);
    }

    public function testLoginWithValidCredentialsAsAdmin()
    {
        // Créer un utilisateur admin
        $admin = new \App\Entities\User([
            'email' => 'admin@example.com',
            'username' => 'administrateur',
            'birthdate' => '1990-01-01',
            'id_permission' => 1,  // Admin
        ]);
        $admin->setPassword('admin123');
        model('UserModel')->save($admin);

        // Tenter la connexion
        $result = $this->post('/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'admin123'
        ]);

        // Admin redirige vers dashboard
        $result->assertRedirectTo('/admin/dashboard');
        $result->assertSessionHas('user_id');
        $result->assertSessionHas('is_logged_in', true);
    }

    public function testLoginWithInvalidEmail()
    {
        $result = $this->post('/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        // Redirige back avec erreur
        $result->assertRedirect();
        $result->assertSessionHas('error', 'Identifiants incorrects');
    }

    public function testLoginWithInvalidPassword()
    {
        // Créer un utilisateur
        $user = new \App\Entities\User([
            'email' => 'user@example.com',
            'username' => 'testuser',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,
        ]);
        $user->setPassword('correct_password');
        model('UserModel')->save($user);

        // Tenter avec mauvais mot de passe
        $result = $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'wrong_password'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Identifiants incorrects');
        $result->assertSessionMissing('user_id');
    }

    public function testLoginWithEmptyCredentials()
    {
        $result = $this->post('/auth/login', [
            'email' => '',
            'password' => ''
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Email et mot de passe requis');
    }

    public function testLoginWithInactiveUser()
    {
        // Créer un utilisateur inactif (soft deleted)
        $user = new \App\Entities\User([
            'email' => 'inactive@example.com',
            'username' => 'inactive',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,
        ]);
        $user->setPassword('password123');
        model('UserModel')->save($user);
        $userId = model('UserModel')->getInsertID();
        // Désactiver l'utilisateur (soft delete)
        model('UserModel')->delete($userId);

        // Tenter la connexion
        $result = $this->post('/auth/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123'
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Compte désactivé');
    }
    public function testLoginCreatesSessionCorrectly()
    {
        // Créer un utilisateur
        $user = new \App\Entities\User([
            'email' => 'test@example.com',
            'username' => 'testuser',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,
        ]);
        $user->setPassword('password123');
        model('UserModel')->save($user);

        // Connexion
        $this->post('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Vérifier toutes les variables de session
        $session = session();
        $this->assertNotNull($session->get('user'));
        $this->assertIsInt($session->get('user_id'));
        $this->assertTrue($session->get('is_logged_in'));
        $this->assertIsInt($session->get('last_activity'));

        // Vérifier que l'objet user en session est correct
        $sessionUser = $session->get('user');
        $this->assertEquals('test@example.com', $sessionUser->email);
        $this->assertEquals('testuser', $sessionUser->username);
    }
    public function testLogoutWorks()
    {
        // Créer un utilisateur
        $user = new \App\Entities\User([
            'email' => 'user@example.com',
            'username' => 'testuser',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,
        ]);
        $user->setPassword('password123');
        model('UserModel')->save($user);

        $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123'
        ]);

        // Maintenant logout
        $result = $this->get('/auth/logout');

        // Vérifications
        $result->assertRedirectTo('/sign-in');
        $result->assertSessionMissing('user');
        $result->assertSessionMissing('user_id');
        $result->assertSessionMissing('is_logged_in');
        $result->assertSessionHas('success', 'Déconnexion réussie');
    }

    public function testAdminPageRequiresAuthentication()
    {
        // Aucun utilisateur connecté
        $result = $this->get('/admin/dashboard');

        // Devrait rediriger vers /sign-in
        $result->assertRedirectTo('/sign-in');
        $result->assertSessionHas('error', 'Vous devez être connecté pour accéder à cette page.');
    }
    public function testAdminPageRequiresAdminPermission()
    {
        // Créer un utilisateur normal (non admin)
        $user = new \App\Entities\User([
            'email' => 'user@example.com',
            'username' => 'testuser',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,
        ]);
        $user->setPassword('password123');
        model('UserModel')->save($user);

        // Recharger l'utilisateur depuis la DB pour avoir toutes les données
        $user = model('UserModel')->find(model('UserModel')->getInsertID());

        // Simuler directement la session
        $result = $this->withSession([
            'user' => $user,
            'user_id' => $user->id,
            'is_logged_in' => true,
            'last_activity' => time(),
        ])->get('/admin/dashboard');

        $result->assertRedirectTo('/forbidden');
        $result->assertSessionHas('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
    public function testSessionExpiresAfterTimeout()
    {
        // Créer un utilisateur normal (non admin)
        $user = new \App\Entities\User([
            'email' => 'user@example.com',
            'username' => 'testuser',
            'birthdate' => '1990-01-01',
            'id_permission' => 2,
        ]);
        $user->setPassword('password123');
        model('UserModel')->save($user);

        // Recharger l'utilisateur depuis la DB pour avoir toutes les données
        $user = model('UserModel')->find(model('UserModel')->getInsertID());

        // Simuler directement la session
        $result = $this->withSession([
            'user' => $user,
            'user_id' => $user->id,
            'is_logged_in' => true,
            'last_activity' => time() - 3601,
        ])->get('/admin/dashboard');

        $result->assertRedirectTo('/sign-in');
        $result->assertSessionHas('error', 'Votre session a expiré. Veuillez vous reconnecter.');
    }


}
