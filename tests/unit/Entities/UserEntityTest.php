<?php

namespace Tests\Unit\Entities;

use CodeIgniter\Test\CIUnitTestCase;
use App\Entities\User;

final class UserEntityTest extends CIUnitTestCase
{
    public function testGetFullName()
    {
        $user = new User([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertEquals('John Doe', $user->getFullName());
    }

    public function testSetPasswordHashesPassword()
    {
        $user = new User();
        $user->setPassword('password123');

        // Le mot de passe ne doit PAS être stocké en clair
        $this->assertNotEquals('password123', $user->password);

        // Doit être un hash bcrypt
        $this->assertStringStartsWith('$2y$', $user->password);
    }

    public function testVerifyPasswordWithCorrectPassword()
    {
        $user = new User();
        $user->setPassword('password123');

        $this->assertTrue($user->verifyPassword('password123'));
    }

    public function testVerifyPasswordWithWrongPassword()
    {
        $user = new User();
        $user->setPassword('password123');

        $this->assertFalse($user->verifyPassword('wrongpassword'));
    }

}
