<?php

namespace Choerulumam\CommitlintPhp\Services;

/**
 * Service for managing Git hooks
 */
class HookService
{
    private const HOOK_MARKER = '# CommitLint PHP Hook';
    private const HOOKS_DIR = '.git/hooks';

    /**
     * @return bool
     */
    public function isGitRepository()
    {
        return is_dir('.git') || (file_exists('.git') && is_file('.git'));
    }

    /**
     * @return bool
     */
    public function hasExistingHooks()
    {
        $hookFiles = ['commit-msg', 'pre-commit', 'pre-push'];

        foreach ($hookFiles as $hook) {
            $hookPath = self::HOOKS_DIR . '/' . $hook;
            if (file_exists($hookPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasInstalledHooks()
    {
        $hookFiles = ['commit-msg', 'pre-commit', 'pre-push'];

        foreach ($hookFiles as $hook) {
            $hookPath = self::HOOKS_DIR . '/' . $hook;
            if (file_exists($hookPath)) {
                $content = file_get_contents($hookPath);
                if ($content !== false && strpos($content, self::HOOK_MARKER) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Install Git hooks based on configuration
     *
     * @param array<string, mixed> $config
     * @return void
     */
    public function installHooks(array $config = [])
    {
        $this->ensureHooksDirectory();

        $hooks = isset($config['hooks']) ? $config['hooks'] : ['commit-msg' => true];

        if (isset($hooks['commit-msg']) && $hooks['commit-msg']) {
            $this->installCommitMsgHook();
        }

        if (isset($hooks['pre-commit']) && $hooks['pre-commit']) {
            $this->installPreCommitHook();
        }

        if (isset($hooks['pre-push']) && $hooks['pre-push']) {
            $this->installPrePushHook();
        }
    }

    /**
     * Uninstall all CommitLint hooks
     *
     * @return void
     */
    public function uninstallHooks()
    {
        $hookFiles = ['commit-msg', 'pre-commit', 'pre-push'];

        foreach ($hookFiles as $hook) {
            $hookPath = self::HOOKS_DIR . '/' . $hook;
            if (file_exists($hookPath)) {
                $content = file_get_contents($hookPath);
                if ($content !== false && strpos($content, self::HOOK_MARKER) !== false) {
                    unlink($hookPath);
                }
            }
        }
    }

    /**
     * Get installed hooks status
     *
     * @return array<string, array<string, mixed>>
     */
    public function getInstalledHooks()
    {
        $hookFiles = ['commit-msg', 'pre-commit', 'pre-push'];
        $hooks = [];

        foreach ($hookFiles as $hook) {
            $hookPath = self::HOOKS_DIR . '/' . $hook;
            $installed = false;

            if (file_exists($hookPath)) {
                $content = file_get_contents($hookPath);
                $installed = $content !== false && strpos($content, self::HOOK_MARKER) !== false;
            }

            $hooks[$hook] = [
                'installed' => $installed,
                'path' => $hookPath,
            ];
        }

        return $hooks;
    }

    /**
     * @return void
     */
    private function ensureHooksDirectory()
    {
        if (!is_dir(self::HOOKS_DIR)) {
            if (!mkdir(self::HOOKS_DIR, 0755, true) && !is_dir(self::HOOKS_DIR)) {
                throw new \RuntimeException('Failed to create hooks directory: ' . self::HOOKS_DIR);
            }
        }

        if (!is_writable(self::HOOKS_DIR)) {
            throw new \RuntimeException('Hooks directory is not writable: ' . self::HOOKS_DIR);
        }
    }

    /**
     * @return void
     */
    private function installCommitMsgHook()
    {
        $hookPath = self::HOOKS_DIR . '/commit-msg';
        $content = $this->createCommitMsgHookContent();

        if (file_put_contents($hookPath, $content) === false) {
            throw new \RuntimeException("Failed to create commit-msg hook: {$hookPath}");
        }

        if (!chmod($hookPath, 0755)) {
            throw new \RuntimeException("Failed to make commit-msg hook executable: {$hookPath}");
        }
    }

    /**
     * @return void
     */
    private function installPreCommitHook()
    {
        $hookPath = self::HOOKS_DIR . '/pre-commit';
        $content = $this->createPreCommitHookContent();

        if (file_put_contents($hookPath, $content) === false) {
            throw new \RuntimeException("Failed to create pre-commit hook: {$hookPath}");
        }

        if (!chmod($hookPath, 0755)) {
            throw new \RuntimeException("Failed to make pre-commit hook executable: {$hookPath}");
        }
    }

    /**
     * @return void
     */
    private function installPrePushHook()
    {
        $hookPath = self::HOOKS_DIR . '/pre-push';
        $content = $this->createPrePushHookContent();

        if (file_put_contents($hookPath, $content) === false) {
            throw new \RuntimeException("Failed to create pre-push hook: {$hookPath}");
        }

        if (!chmod($hookPath, 0755)) {
            throw new \RuntimeException("Failed to make pre-push hook executable: {$hookPath}");
        }
    }

    /**
     * @return string
     */
    private function findPhpBinary()
    {
        // Try common locations
        $candidates = [
            PHP_BINARY,
            '/usr/bin/php',
            '/usr/local/bin/php',
        ];

        foreach ($candidates as $binary) {
            if (file_exists($binary) && is_executable($binary)) {
                return $binary;
            }
        }

        return 'php'; // Fallback to PATH
    }

    /**
     * @return string
     */
    private function findCommitlintBinary()
    {
        $cwd = getcwd();
        $candidates = [
            $cwd . '/vendor/bin/commitlint',
            $cwd . '/bin/commitlint',
        ];

        foreach ($candidates as $binary) {
            if (file_exists($binary)) {
                return $binary;
            }
        }

        return 'vendor/bin/commitlint';
    }

    /**
     * @return string
     */
    private function createCommitMsgHookContent()
    {
        $marker = self::HOOK_MARKER;
        $phpBinary = $this->findPhpBinary();
        $commitlintBinary = $this->findCommitlintBinary();

        $hook = '#!/bin/sh' . "\n";
        $hook .= $marker . "\n\n";
        $hook .= '# Check if CommitLint is available' . "\n";
        $hook .= 'if [ ! -f "' . $commitlintBinary . '" ]; then' . "\n";
        $hook .= '    echo "⚠️  CommitLint not found. Skipping validation."' . "\n";
        $hook .= '    exit 0' . "\n";
        $hook .= 'fi' . "\n\n";
        $hook .= '# Check if we\'re in a rebase/merge/cherry-pick' . "\n";
        $hook .= 'if [ -f ".git/MERGE_HEAD" ] || [ -f ".git/REBASE_HEAD" ] || [ -f ".git/CHERRY_PICK_HEAD" ]; then' . "\n";
        $hook .= '    echo "🔄 In rebase/merge/cherry-pick mode. Skipping validation."' . "\n";
        $hook .= '    exit 0' . "\n";
        $hook .= 'fi' . "\n\n";
        $hook .= '# Validate commit message' . "\n";
        $hook .= '"' . $phpBinary . '" "' . $commitlintBinary . '" validate --file="$1"' . "\n\n";
        $hook .= '# Exit with the same code as the validation' . "\n";
        $hook .= 'exit $?' . "\n";

        return $hook;
    }

    /**
     * @return string
     */
    private function createPreCommitHookContent()
    {
        $marker = self::HOOK_MARKER;

        $hook = '#!/bin/sh' . "\n";
        $hook .= $marker . "\n\n";
        $hook .= 'echo "🔍 Running pre-commit checks..."' . "\n\n";
        $hook .= '# Add your custom pre-commit checks here' . "\n";
        $hook .= '# Example: vendor/bin/phpunit --testsuite=quick' . "\n\n";
        $hook .= 'exit 0' . "\n";

        return $hook;
    }

    /**
     * @return string
     */
    private function createPrePushHookContent()
    {
        $marker = self::HOOK_MARKER;

        $hook = '#!/bin/sh' . "\n";
        $hook .= $marker . "\n\n";
        $hook .= 'echo "🚀 Running pre-push checks..."' . "\n\n";
        $hook .= '# Add your custom pre-push checks here' . "\n";
        $hook .= '# Example: vendor/bin/phpunit' . "\n\n";
        $hook .= 'exit 0' . "\n";

        return $hook;
    }
}
