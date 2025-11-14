<?php

namespace Choerulumam\CommitlintPhp\Commands;

use Choerulumam\CommitlintPhp\Services\ConfigService;
use Choerulumam\CommitlintPhp\Services\HookService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to show status of installed hooks and configuration
 */
class StatusCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'status';

    /** @var HookService */
    private $hookService;

    /** @var ConfigService */
    private $configService;

    /**
     * @param HookService $hookService
     * @param ConfigService $configService
     */
    public function __construct(HookService $hookService, ConfigService $configService)
    {
        parent::__construct();
        $this->hookService = $hookService;
        $this->configService = $configService;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('status')
            ->setDescription('Show status of installed hooks and configuration');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('📋 CommitLint PHP Status');

        try {
            if (!$this->hookService->isGitRepository()) {
                $io->error('Not a Git repository!');
                return 1;
            }

            // Show hooks status
            $this->displayHooksStatus($io);

            // Show configuration status
            $this->displayConfigurationStatus($io);

            return 0;
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * @param SymfonyStyle $io
     * @return void
     */
    private function displayHooksStatus(SymfonyStyle $io)
    {
        $io->section('🪝 Git Hooks Status');

        $hooks = $this->hookService->getInstalledHooks();
        $rows = [];

        foreach ($hooks as $name => $info) {
            $rows[] = [
                $name,
                $info['installed'] ? '<fg=green>✓ Installed</>' : '<fg=yellow>Not installed</>',
                $info['path'],
            ];
        }

        $io->table(['Hook', 'Status', 'Path'], $rows);
    }

    /**
     * @param SymfonyStyle $io
     * @return void
     */
    private function displayConfigurationStatus(SymfonyStyle $io)
    {
        $io->section('⚙️  Configuration');

        $configPath = $this->configService->getConfigPath();
        $exists = $this->configService->configExists();

        $io->writeln(sprintf('Config file: <info>%s</info>', $configPath));
        $io->writeln(sprintf('Status: %s', $exists ? '<fg=green>✓ Exists</>' : '<fg=yellow>Not found (using defaults)</>'));

        if ($exists) {
            $config = $this->configService->loadConfig();
            
            if (isset($config['rules']['type']['allowed'])) {
                $io->newLine();
                $io->writeln('<comment>Allowed types:</comment>');
                $io->listing($config['rules']['type']['allowed']);
            }
        } else {
            $io->newLine();
            $io->writeln('<comment>💡 Tip: Run "commitlint install" to create a default configuration file</comment>');
        }
    }
}
