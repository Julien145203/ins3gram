<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

final class ExampleTest extends CIUnitTestCase
{
    public function testAddition()
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }

    public function testStringConcatenation()
    {
        $hello = 'Hello';
        $world = 'World';

        $this->assertEquals('Hello World', $hello . ' ' . $world);
    }
}
