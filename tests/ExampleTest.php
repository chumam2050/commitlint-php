<?php

namespace Choerulumam\CommitlintPhp\Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testPhpVersion()
    {
        $this->assertGreaterThanOrEqual('7.3.0', PHP_VERSION, 'PHP version should be 7.3 or higher');
    }
}
