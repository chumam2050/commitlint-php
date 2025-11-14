# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased] - 2025-11-14

### Added - DevContainer Setup
- Complete DevContainer configuration for PHP development
- Support for PHP 7.3 through 8.3
- Automatic Xdebug configuration based on PHP version
- Docker Compose setup with configurable PHP version
- PHPUnit integration with coverage support
- VS Code extensions and settings for PHP development
- GitHub Actions workflow for multi-version testing

### DevContainer Features
- **Dockerfile**: Multi-version PHP support with build args
  - PHP 7.3, 7.4, 8.0, 8.1 (default), 8.2, 8.3
  - Auto-configured Xdebug (legacy for 7.x, modern for 8.x)
  - Composer pre-installed
  - Non-root user (vscode) for security
  
- **VS Code Integration**:
  - Intelephense for PHP IntelliSense
  - PHP Debug with Xdebug
  - PHPUnit Test Explorer
  - GitLens
  - EditorConfig support

### Configuration Files
- `phpunit.xml`: PHPUnit configuration with coverage
- `.editorconfig`: Code style consistency
- `.github/workflows/tests.yml`: CI/CD for all PHP versions
- `Makefile`: Development shortcuts
- `.vscode/tasks.json`: VS Code task automation
- `.vscode/extensions.json`: Recommended extensions

### Documentation
- `.devcontainer/README.md`: DevContainer setup guide
- `COMPATIBILITY.md`: PHP version compatibility matrix
- `QUICKSTART.md`: Quick start guide
- `README.md`: Updated with installation and usage

### Tools & Scripts
- `switch-php-version.sh`: Helper script to change PHP version
- Make commands: install, test, coverage, clean, switch-php

### Testing
- `tests/ExampleTest.php`: Example test case
- `tests/CompatibilityTest.php`: PHP version compatibility tests
- Multi-version CI/CD pipeline

### Dependencies
- Updated `composer.json`:
  - PHP requirement: `>=7.3`
  - PHPUnit: `^9.5 || ^8.0` (in require-dev)
  - Added autoload-dev for tests
  - Added test scripts

### Changed
- Moved PHPUnit from `require` to `require-dev`
- Updated `.gitignore` with comprehensive exclusions
- PHP version configurable via docker-compose.yml

## Version Matrix

| Component | Version | Notes |
|-----------|---------|-------|
| PHP | 7.3 - 8.3 | Configurable via DevContainer |
| PHPUnit | 8.x - 9.x | Auto-selected based on PHP version |
| Xdebug | Latest | Auto-configured per PHP version |
| Composer | Latest | From official image |
