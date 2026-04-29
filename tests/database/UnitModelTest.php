<?php

namespace Tests\Database;

use App\Models\UnitModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class UnitModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $DBGroup = 'tests';
    protected $namespace = 'App';

    private UnitModel $unitModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->unitModel = new UnitModel();
    }

    public function testCanCreateUnit(): void
    {
        $id = $this->unitModel->insert(['name' => 'cl']);

        $this->assertIsInt($id);
        $this->assertTrue($id > 0);
        $this->seeInDatabase('unit', ['id' => $id, 'name' => 'cl']);
    }

    public function testCanReadAndDeleteUnit(): void
    {
        $id = $this->unitModel->insert(['name' => 'to-delete']);
        $this->unitModel->delete($id);

        $deleted = $this->unitModel->find($id);

        $this->assertNull($deleted);
    }

    public function testRejectsEmptyName(): void
    {
        $result = $this->unitModel->insert(['name' => '']);

        $this->assertFalse($result);
        $this->assertTrue(isset($this->unitModel->errors()['name']));
    }

    public function testRejectsTooLongName(): void
    {
        $name = str_repeat('a', 256);
        $result = $this->unitModel->insert(['name' => $name]);

        $this->assertFalse($result);
        $this->assertTrue(isset($this->unitModel->errors()['name']));
    }
}