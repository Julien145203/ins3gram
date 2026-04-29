<?php

namespace Tests\Database;

use App\Models\BrandModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class BrandModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = 'App';

    private BrandModel $brandModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->brandModel = new BrandModel();
    }

    public function testPeutCreerUneMarque(): void
    {
        $id = $this->brandModel->insert(['name' => 'Brand Test']);

        $this->assertNotFalse($id);
        $this->assertTrue((int) $id > 0);
        $this->seeInDatabase('brand', ['id' => (int) $id, 'name' => 'Brand Test']);
    }

    public function testPeutLireEtMettreAJourUneMarque(): void
    {
        $id = $this->brandModel->insert(['name' => 'Before Update']);
        $this->brandModel->update((int) $id, ['name' => 'After Update']);

        $brand = $this->brandModel->find((int) $id);

        $this->assertEquals('After Update', $brand['name']);
    }

    public function testRefuseUneMarqueSansNom(): void
    {
        $result = $this->brandModel->insert(['image' => 'without-name.png']);

        $this->assertFalse($result);
        $this->assertTrue(isset($this->brandModel->errors()['name']));
    }

    public function testRefuseUneMarqueEnDouble(): void
    {
        $this->brandModel->insert(['name' => 'Unique Brand']);
        $result = $this->brandModel->insert(['name' => 'Unique Brand']);

        $this->assertFalse($result);
        $this->assertTrue(isset($this->brandModel->errors()['name']));
    }
}