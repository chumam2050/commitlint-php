<?php

namespace Choerulumam\CommitlintPhp\Commands;

use Choerulumam\CommitlintPhp\Services\HookService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to uninstall Git hooks
 */
class UninstallCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'uninstall';

    /** @var HookService */
    private $hookService;

    /**
     * @param HookService $hookService
     */
    public function __construct(HookService $hookService)
    {
        parent::__construct();
        $this->hookService = $hookService;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Uninstall Git hooks')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force uninstallation without confirmation'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption('force');

        $io->title('🗑️  CommitLint PHP - Uninstalling Git Hooks');

        try {
            if (!$this->hookService->isGitRepository()) {
                $io->error('Not a Git repository!');
                return 1;
            }

            if (!$this->hookService->hasInstalledHooks()) {
                $io->warning('No CommitLint hooks are installed.');
                return 0;
            }

            if (!$force && !$io->confirm('Are you sure you want to uninstall all CommitLint hooks?', false)) {
                $io->writeln('Uninstallation cancelled.');
                return 0;
            }

            $this->hookService->uninstallHooks();

            $io->success('✓ All CommitLint hooks have been uninstalled');

            return 0;
        } catch (\Exception $e) {
            $io->error('Failed to uninstall hooks: ' . $e->getMessage());
            return 1;
        }
    }
}
