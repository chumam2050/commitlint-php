<?php

namespace Choerulumam\CommitlintPhp\Tests;

use PHPUnit\Framework\TestCase;

class CompatibilityTest extends TestCase
{
    public function testPhpVersion()
    {
        $this->assertGreaterThanOrEqual('7.3.0', PHP_VERSION, 'PHP version should be 7.3 or higher');
    }

    public function testRequiredExtensions()
    {
        $this->assertTrue(extension_loaded('json'), 'JSON extension should be loaded');
        $this->assertTrue(extension_loaded('mbstring'), 'Mbstring extension should be loaded');
    }

    public function testComposerAutoload()
    {
        $this->assertTrue(
            class_exists('PHPUnit\\Framework\\TestCase'),
            'Composer autoload should work correctly'
        );
    }

    public function testApplicationClass()
    {
        $this->assertTrue(
            class_exists('Choerulumam\\CommitlintPhp\\Application'),
            'Application class should be autoloaded'
        );
    }

    public function testServicesExist()
    {
        $this->assertTrue(class_exists('Choerulumam\\CommitlintPhp\\Services\\ValidationService'));
        $this->assertTrue(class_exists('Choerulumam\\CommitlintPhp\\Services\\ConfigService'));
        $this->assertTrue(class_exists('Choerulumam\\CommitlintPhp\\Services\\HookService'));
    }

    public function testModelsExist()
    {
        $this->assertTrue(class_exists('Choerulumam\\CommitlintPhp\\Models\\CommitMessage'));
        $this->assertTrue(class_exists('Choerulumam\\CommitlintPhp\\Models\\ValidationResult'));
    }
}
