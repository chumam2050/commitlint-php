<?php

namespace Choerulumam\CommitlintPhp\Commands;

use Choerulumam\CommitlintPhp\Services\ConfigService;
use Choerulumam\CommitlintPhp\Services\ValidationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to validate commit messages
 */
class ValidateCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'validate';

    private const DEFAULT_COMMIT_MSG_FILE = '.git/COMMIT_EDITMSG';

    /** @var ValidationService */
    private $validationService;

    /** @var ConfigService */
    private $configService;

    /**
     * @param ValidationService $validationService
     * @param ConfigService $configService
     */
    public function __construct(ValidationService $validationService, ConfigService $configService)
    {
        parent::__construct();
        $this->validationService = $validationService;
        $this->configService = $configService;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Validate a commit message')
            ->addArgument(
                'message',
                InputArgument::OPTIONAL,
                'Commit message to validate'
            )
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Read commit message from file'
            )
            ->addOption(
                'quiet',
                'q',
                InputOption::VALUE_NONE,
                'Suppress output (exit code only)'
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
        $quiet = $input->getOption('quiet');

        try {
            $message = $this->getMessage($input);
            $config = $this->configService->loadConfig();

            $result = $this->validationService->validate($message, $config);

            if ($result->isValid()) {
                if (!$quiet) {
                    $io->success('✓ Commit message is valid');
                }
                return 0;
            }

            if (!$quiet) {
                $io->error('✗ Commit message validation failed');
                $io->section('Errors:');
                foreach ($result->getErrors() as $error) {
                    $io->writeln('  • ' . $error);
                }

                $this->showExamples($io, $config);
            }

            return 1;
        } catch (\Exception $e) {
            if (!$quiet) {
                $io->error('Validation error: ' . $e->getMessage());
            }
            return 1;
        }
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function getMessage(InputInterface $input)
    {
        $message = $input->getArgument('message');
        if ($message !== null) {
            return $message;
        }

        $file = $input->getOption('file');
        if ($file !== null) {
            return $this->readMessageFromFile($file);
        }

        // Default: read from .git/COMMIT_EDITMSG
        if (file_exists(self::DEFAULT_COMMIT_MSG_FILE)) {
            return $this->readMessageFromFile(self::DEFAULT_COMMIT_MSG_FILE);
        }

        throw new \RuntimeException('No commit message provided. Use argument or --file option.');
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function readMessageFromFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("File not found: {$filePath}");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException("Failed to read file: {$filePath}");
        }

        return trim($content);
    }

    /**
     * @param SymfonyStyle $io
     * @param array<string, mixed> $config
     * @return void
     */
    private function showExamples(SymfonyStyle $io, array $config)
    {
        $io->section('💡 Examples of valid commit messages:');

        $typeConfig = isset($config['rules']['type']) ? $config['rules']['type'] : [];
        $allowedTypes = isset($typeConfig['allowed']) ? $typeConfig['allowed'] : ['feat', 'fix', 'docs'];

        $examples = [
            sprintf('%s: add new user authentication', $allowedTypes[0]),
            sprintf('%s: resolve login validation issue', isset($allowedTypes[1]) ? $allowedTypes[1] : 'fix'),
            sprintf('%s(auth): implement JWT token validation', $allowedTypes[0]),
        ];

        foreach ($examples as $example) {
            $io->writeln('  • ' . $example);
        }

        $io->newLine();
        $io->writeln('For more information, check your .commitlintrc.json configuration file.');
    }
}
