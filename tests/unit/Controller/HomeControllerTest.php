<?php

namespace Tests\Unit\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class HomeControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testPageAccueilRetourne200(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testPageAccueilContientLeNomDuSite(): void
    {
        $result = $this->get('/');
        $result->assertSee('Ins3gram');
    }

    public function testPageAccueilContientLeLienVersLesRecettes(): void
    {
        $result = $this->get('/');
        $result->assertSee('Voir les recettes');
    }

    public function testRouteInconnueRetourne404(): void
    {
        $result = $this->get('/route-inexistante-test');
        $result->assertStatus(404);
    }
}