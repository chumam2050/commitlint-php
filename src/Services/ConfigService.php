<?php

namespace Choerulumam\CommitlintPhp\Services;

/**
 * Service for loading and managing configuration
 */
class ConfigService
{
    private const CONFIG_FILE = '.commitlintrc.json';
    private const MAX_CONFIG_SIZE = 102400; // 100KB

    /**
     * Load configuration from file or return defaults
     *
     * @return array<string, mixed>
     */
    public function loadConfig()
    {
        $configPath = $this->getConfigPath();
        
        if (file_exists($configPath)) {
            return $this->loadFromFile($configPath);
        }

        // Try composer.json
        $composerConfig = $this->loadFromComposer();
        if (!empty($composerConfig)) {
            return $this->mergeWithDefaults($composerConfig);
        }

        return $this->getDefaultConfig();
    }

    /**
     * @param string $path
     * @return array<string, mixed>
     */
    private function loadFromFile($path)
    {
        if (!is_readable($path)) {
            throw new \RuntimeException("Configuration file is not readable: {$path}");
        }

        $size = filesize($path);
        if ($size === false || $size > self::MAX_CONFIG_SIZE) {
            throw new \RuntimeException("Configuration file too large (max 100KB)");
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException("Failed to read configuration file: {$path}");
        }

        $config = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON in configuration file: " . json_last_error_msg());
        }

        if (!is_array($config)) {
            throw new \RuntimeException("Configuration must be a JSON object");
        }

        return $this->mergeWithDefaults($config);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFromComposer()
    {
        $composerPath = getcwd() . '/composer.json';
        
        if (!file_exists($composerPath)) {
            return [];
        }

        $content = file_get_contents($composerPath);
        if ($content === false) {
            return [];
        }

        $composer = json_decode($content, true);
        if (!is_array($composer)) {
            return [];
        }

        if (isset($composer['extra']['commitlint-php']) && is_array($composer['extra']['commitlint-php'])) {
            return $composer['extra']['commitlint-php'];
        }

        return [];
    }

    /**
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private function mergeWithDefaults(array $config)
    {
        $defaults = $this->getDefaultConfig();

        return array_replace_recursive($defaults, $config);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefaultConfig()
    {
        return [
            'rules' => [
                'type' => [
                    'required' => true,
                    'allowed' => [
                        'feat',
                        'fix',
                        'docs',
                        'style',
                        'refactor',
                        'perf',
                        'test',
                        'chore',
                        'ci',
                        'build',
                        'revert',
                    ],
                ],
                'scope' => [
                    'required' => false,
                    'allowed' => [],
                ],
                'subject' => [
                    'min_length' => 1,
                    'max_length' => 100,
                    'case' => 'any',
                    'end_with_period' => false,
                ],
                'body' => [
                    'max_line_length' => 100,
                    'leading_blank' => true,
                ],
                'footer' => [
                    'leading_blank' => true,
                ],
            ],
            'hooks' => [
                'commit-msg' => true,
                'pre-commit' => false,
                'pre-push' => false,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $config
     * @return bool
     */
    public function saveConfig(array $config)
    {
        $path = $this->getConfigPath();
        $json = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            throw new \RuntimeException("Failed to encode configuration: " . json_last_error_msg());
        }

        $result = file_put_contents($path, $json);

        return $result !== false;
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return getcwd() . '/' . self::CONFIG_FILE;
    }

    /**
     * @return bool
     */
    public function configExists()
    {
        return file_exists($this->getConfigPath());
    }

    /**
     * Create default configuration file
     *
     * @return bool
     */
    public function createDefaultConfig()
    {
        if ($this->configExists()) {
            return false;
        }

        return $this->saveConfig($this->getDefaultConfig());
    }
}
