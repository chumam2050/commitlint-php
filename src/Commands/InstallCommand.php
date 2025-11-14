<?php

namespace Choerulumam\CommitlintPhp\Commands;

use Choerulumam\CommitlintPhp\Services\ConfigService;
use Choerulumam\CommitlintPhp\Services\HookService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to install Git hooks
 */
class InstallCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'install';

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
            ->setName('install')
            ->setDescription('Install Git hooks for commit message validation')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force installation even if hooks already exist'
            )
            ->addOption(
                'skip-config',
                null,
                InputOption::VALUE_NONE,
                'Skip creating default configuration file'
            )
            ->addOption(
                'quiet',
                'q',
                InputOption::VALUE_NONE,
                'Suppress output messages'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption('force');
        $skipConfig = $input->getOption('skip-config');
        $quiet = $input->getOption('quiet');

        if (!$quiet) {
            $io->title('🎯 CommitLint PHP - Installing Git Hooks');
        }

        try {
            // Check if in Git repository
            if (!$this->hookService->isGitRepository()) {
                if (!$quiet) {
                    $io->error('Not a Git repository!');
                }
                return 1;
            }

            // Check existing hooks
            if (!$force && $this->hookService->hasExistingHooks()) {
                if ($quiet) {
                    // In quiet mode, just skip if hooks exist
                    return 0;
                }
                if (!$io->confirm('Existing hooks found. Continue and overwrite?', false)) {
                    $io->warning('Installation cancelled.');
                    return 0;
                }
            }

            // Install hooks
            $config = $this->configService->loadConfig();
            $this->hookService->installHooks($config);

            if (!$quiet) {
                $io->success('✓ Git hooks installed successfully!');
            }

            // Create config if needed
            if (!$skipConfig && !$this->configService->configExists()) {
                if ($quiet) {
                    // In quiet mode, auto-create config without prompting
                    $this->configService->createDefaultConfig();
                } elseif ($io->confirm('Create default configuration file (.commitlintrc.json)?', true)) {
                    $this->configService->createDefaultConfig();
                    $io->writeln('✓ Configuration file created: .commitlintrc.json');
                }
            }

            if (!$quiet) {
                $io->section('📋 Next Steps');
                $io->listing([
                    'Make a commit to test the hook',
                    'Edit .commitlintrc.json to customize rules',
                    'Run "commitlint status" to see installed hooks',
                ]);
            }

            return 0;
        } catch (\Exception $e) {
            $io->error('Failed to install hooks: ' . $e->getMessage());
            return 1;
        }
    }
}
