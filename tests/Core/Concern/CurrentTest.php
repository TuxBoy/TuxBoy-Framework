<?php
namespace Test\Core\Concern;

use Core\Concern\Current;
use PHPUnit\Framework\TestCase;

class FakeTest {

    use Current;

    public $test;

    public function __construct()
    {
        $this->test += 1;
    }

}

class FakeWithoutCurrent {

}

class CurrentTest extends TestCase
{

    public function testReturnSameInstance()
    {
        $current = FakeTest::current();
        $this->assertInstanceOf(FakeTest::class, $current);
    }

    public function testSingleton()
    {
        $current1 = FakeTest::current();
        $current2 = FakeTest::current();

        $this->assertEquals(1, $current1->test);
        $this->assertEquals(1, $current2->test);
    }

}