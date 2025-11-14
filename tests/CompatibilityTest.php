<?php

namespace choerulumam\CommitlintPhp\Tests;

use PHPUnit\Framework\TestCase;

class CompatibilityTest extends TestCase
{
    public function testPhpVersion(): void
    {
        $this->assertGreaterThanOrEqual('7.3.0', PHP_VERSION, 'PHP version should be 7.3 or higher');
    }

    public function testRequiredExtensions(): void
    {
        $this->assertTrue(extension_loaded('zip'), 'ZIP extension should be loaded');
        $this->assertTrue(extension_loaded('json'), 'JSON extension should be loaded');
    }

    public function testComposerAutoload(): void
    {
        $this->assertTrue(
            class_exists('PHPUnit\Framework\TestCase'),
            'Composer autoload should work correctly'
        );
    }
}
