# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-11-14

### Added - CommitLint PHP Implementation

Complete implementation of CommitLint for PHP with support for PHP 7.3+.

#### Core Features
- **Commit Message Validation** - Full support for conventional commit message format
- **Git Hooks Management** - Install, uninstall, and manage Git hooks seamlessly
- **Flexible Configuration** - JSON-based configuration via `.commitlintrc.json` or `composer.json`
- **PHP 7.3+ Compatible** - Works with PHP 7.3 through PHP 8.3
- **Security Features** - Input validation, safe command execution, path traversal protection

#### Components

**Models:**
- `CommitMessage` - Parse and analyze commit messages
- `ValidationResult` - Encapsulate validation results

**Services:**
- `ValidationService` - Validate commit messages against rules
- `ConfigService` - Load and manage configuration
- `HookService` - Install and manage Git hooks

**Commands:**
- `InstallCommand` - Install Git hooks and create configuration
- `ValidateCommand` - Validate commit messages
- `UninstallCommand` - Remove Git hooks
- `StatusCommand` - Show hooks and configuration status

#### Configuration
- Support for `.commitlintrc.json` configuration file
- Support for `composer.json` extra configuration
- Comprehensive rule system:
  - Type validation (required, allowed types)
  - Scope validation (required, allowed scopes)
  - Subject validation (length, case, punctuation)
  - Body validation (line length, blank lines)
  - Footer validation (blank lines)
- Hook configuration (commit-msg, pre-commit, pre-push)

#### Validation Features
- Conventional commits format validation
- Custom type and scope enforcement
- Subject length and case validation
- Body and footer formatting rules
- Auto-skip for merge, revert, fixup commits
- Breaking change detection
- Comprehensive error messages

#### Documentation
- Complete README with examples
- Detailed USAGE guide
- Configuration examples (minimal, default, strict)
- DevContainer setup for development
- PHP compatibility matrix
- Contributing guidelines

#### Testing
- PHPUnit test suite
- Validation service tests
- Compatibility tests
- Example test cases

### DevContainer Features
- PHP 7.3 - 8.3 support
- Auto-configured Xdebug (legacy for 7.x, modern for 8.x)
- Composer pre-installed
- VS Code integration with extensions
- GitHub Actions workflow for multi-version testing

## Version Matrix

| Component | Version | Notes |
|-----------|---------|-------|
| PHP | 7.3 - 8.3 | Configurable via DevContainer |
| Symfony Console | 4.4+ | Auto-selected based on PHP version |
| PHPUnit | 8.x - 9.x | Auto-selected based on PHP version |

## Acknowledgments

Inspired by:
- [husky](https://github.com/typicode/husky) - Git hooks made easy
- [commitlint](https://github.com/conventional-changelog/commitlint) - Lint commit messages
- [dev-kraken/php-commitlint](https://github.com/dev-kraken/php-commitlint) - PHP implementation reference
