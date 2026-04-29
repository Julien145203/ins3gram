<?php

namespace Tests\Unit\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class ContactControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testPageContactRetourne200(): void
    {
        $result = $this->get('/contactez-nous');
        $result->assertStatus(200);
    }

    public function testPageContactContientLeTitreAttendu(): void
    {
        $result = $this->get('/contactez-nous');
        $result->assertSee('Contactez-nous');
    }

    public function testEnvoiContactAvecDonneesInvalidesRedirige(): void
    {
        $result = $this->post('/contactez-nous/send', [
            'subject' => 'Hi',
            'email' => 'email-invalide',
            'message' => 'court',
        ]);
        $result->assertRedirectTo('/contactez-nous');
    }

    public function testEnvoiContactSansDonneesRedirige(): void
    {
        $result = $this->post('/contactez-nous/send', []);
        $result->assertRedirectTo('/contactez-nous');
    }
}