<?php

namespace Tests\Unit\Helpers;

use CodeIgniter\Test\CIUnitTestCase;

final class UtilsHelperTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        helper('utils');
    }

    public function testGenereUnSlugAvecUnTexteSimple(): void
    {
        $this->assertEquals('bonjour-le-monde', generateSlug('Bonjour le Monde'));
    }

    public function testGenereUnSlugSansAccents(): void
    {
        $this->assertEquals('creme-brulee-a-l-orange', generateSlug('Crème Brûlée à l\'Orange'));
    }

    public function testGenereUnSlugAvecSymbolesEtEspaces(): void
    {
        $slug = generateSlug(' Hello ### World !!!  ');

        $this->assertEquals('-hello-world-', $slug);
        $this->assertFalse(str_contains($slug, ' '));
    }

    public function testGenereUnSlugVidePourChaineVide(): void
    {
        $this->assertEquals('', generateSlug(''));
    }
}