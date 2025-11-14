<?php

namespace Choerulumam\CommitlintPhp;

use Choerulumam\CommitlintPhp\Commands\InstallCommand;
use Choerulumam\CommitlintPhp\Commands\UninstallCommand;
use Choerulumam\CommitlintPhp\Commands\ValidateCommand;
use Choerulumam\CommitlintPhp\Commands\StatusCommand;
use Choerulumam\CommitlintPhp\Services\ConfigService;
use Choerulumam\CommitlintPhp\Services\HookService;
use Choerulumam\CommitlintPhp\Services\ValidationService;
use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Main application class
 */
class Application extends ConsoleApplication
{
    private const NAME = 'CommitLint PHP';
    private const VERSION = '1.0.0';

    /**
     * @param string|null $name
     * @param string|null $version
     */
    public function __construct($name = null, $version = null)
    {
        parent::__construct(
            $name ?? self::NAME,
            $version ?? self::VERSION
        );

        $this->addCommands($this->getCommands());
        $this->setDefaultCommand('status');
    }

    /**
     * @return array<int, \Symfony\Component\Console\Command\Command>
     */
    private function getCommands()
    {
        // Initialize services
        $configService = new ConfigService();
        $hookService = new HookService();
        $validationService = new ValidationService();

        return [
            new InstallCommand($hookService, $configService),
            new UninstallCommand($hookService),
            new ValidateCommand($validationService, $configService),
            new StatusCommand($hookService, $configService),
        ];
    }

    /**
     * @return string
     */
    public function getLongVersion(): string
    {
        return sprintf(
            '<info>%s</info> version <comment>%s</comment>',
            $this->getName(),
            $this->getVersion()
        );
    }
}
