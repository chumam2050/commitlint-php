# CommitLint PHP 🎯

A powerful Git hooks and commit message linting tool for PHP projects - combining the best of husky and commitlint in a native PHP implementation.

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.3-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/choerulumam/commitlint-php)](https://packagist.org/packages/choerulumam/commitlint-php)
[![Packagist Downloads](https://img.shields.io/packagist/dt/choerulumam/commitlint-php)](https://packagist.org/packages/choerulumam/commitlint-php)
[![Tests](https://img.shields.io/badge/tests-passing-brightgreen.svg)](tests/)
[![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](tests/)


## 🚀 Features

- **🎯 Commit Message Validation** - Enforce conventional commit format with customizable rules
- **🪝 Git Hooks Management** - Easy installation and management of Git hooks
- **⚙️ Flexible Configuration** - Configure via `.commitlintrc.json` or `composer.json`
- **🔧 Developer Friendly** - Beautiful CLI output with helpful error messages
- **📦 Minimal Dependencies** - Only requires Symfony Console component
- **🧪 Fully Tested** - Comprehensive test suite with PHPUnit
- **🎨 PHP 7.3+ Compatible** - Works with PHP 7.3 through PHP 8.3
- **🔒 Security First** - Built-in security features and input validation

## Requirements

- PHP >= 7.3
- Composer
- Git

## 📦 Installation

Install via Composer:

```bash
composer require --dev choerulumam/commitlint-php
```

## 🔧 Quick Start

### 1. Install Git Hooks

```bash
vendor/bin/commitlint install
```

### 2. Start Making Commits!

```bash
# ✅ Valid commits
git commit -m "feat: add user authentication"
git commit -m "fix(auth): resolve login validation issue"
git commit -m "docs: update README with examples"

# ❌ Invalid commits (will be rejected)
git commit -m "added new feature"  # Missing type
git commit -m "FIX: something"     # Invalid type case
```

## 📋 Commands

### Install Hooks

Install Git hooks and create default configuration:

```bash
vendor/bin/commitlint install [options]
```

**Options:**
- `--force, -f` - Force installation even if hooks already exist
- `--skip-config` - Skip creating default configuration file

### Validate Commit Message

Validate commit messages against your configuration:

```bash
# Validate current commit message (from .git/COMMIT_EDITMSG)
vendor/bin/commitlint validate

# Validate specific message
vendor/bin/commitlint validate "feat: add new feature"

# Validate from file
vendor/bin/commitlint validate --file=commit.txt

# Quiet mode (exit code only)
vendor/bin/commitlint validate --quiet
```

**Options:**
- `--file, -f` - Read commit message from specific file
- `--quiet, -q` - Suppress output (exit code only)

### Show Status

Display installed hooks and configuration:

```bash
vendor/bin/commitlint status
```

### Uninstall

Remove all installed Git hooks:

```bash
vendor/bin/commitlint uninstall [--force]
```

## ⚙️ Configuration

CommitLint PHP can be configured via `.commitlintrc.json` file or within your `composer.json`. The tool automatically merges your custom configuration with sensible defaults.

### Configuration File Priority

1. `.commitlintrc.json` in your project root
2. `extra.commitlint-php` in `composer.json`
3. Default configuration

### Complete Configuration Reference

```json
{
  "rules": {
    "type": {
      "required": true,
      "allowed": ["feat", "fix", "docs", "style", "refactor", "perf", "test", "chore", "ci", "build", "revert"]
    },
    "scope": {
      "required": false,
      "allowed": []
    },
    "subject": {
      "min_length": 1,
      "max_length": 100,
      "case": "any",
      "end_with_period": false
    },
    "body": {
      "max_line_length": 100,
      "leading_blank": true
    },
    "footer": {
      "leading_blank": true
    }
  },
  "hooks": {
    "commit-msg": true,
    "pre-commit": false,
    "pre-push": false
  }
}
```

### Configuration Options

#### Type Rules (`rules.type`)
- **`required`** (boolean, default: `true`) - Whether commit type is required
- **`allowed`** (array, default: see above) - Array of allowed commit types

#### Scope Rules (`rules.scope`)
- **`required`** (boolean, default: `false`) - Whether commit scope is required
- **`allowed`** (array, default: `[]`) - Array of allowed scopes (empty = any scope allowed)

#### Subject Rules (`rules.subject`)
- **`min_length`** (int, default: `1`) - Minimum subject length
- **`max_length`** (int, default: `100`) - Maximum subject length
- **`case`** (string, default: `"any"`) - Case requirement: `"lower"`, `"upper"`, or `"any"`
- **`end_with_period`** (boolean, default: `false`) - Whether subject must end with period

#### Body Rules (`rules.body`)
- **`max_line_length`** (int, default: `100`) - Maximum line length for body (0 = no limit)
- **`leading_blank`** (boolean, default: `true`) - Require blank line between subject and body

#### Footer Rules (`rules.footer`)
- **`leading_blank`** (boolean, default: `true`) - Require blank line between body and footer

#### Hook Configuration (`hooks`)
Control which Git hooks are installed:

```json
{
  "hooks": {
    "commit-msg": true,    // Validate commit messages
    "pre-commit": false,   // Run before commits
    "pre-push": false      // Run before pushes
  }
}
```

## 📖 Example Configurations

### Minimal Configuration

```json
{
  "rules": {
    "type": {
      "allowed": ["feat", "fix", "docs", "chore"]
    },
    "subject": {
      "max_length": 50
    }
  }
}
```

### Strict Configuration

```json
{
  "rules": {
    "type": {
      "required": true,
      "allowed": ["feat", "fix", "docs", "test", "refactor", "chore"]
    },
    "scope": {
      "required": true,
      "allowed": ["auth", "api", "ui", "db", "config", "test", "docs"]
    },
    "subject": {
      "min_length": 10,
      "max_length": 50,
      "case": "lower",
      "end_with_period": false
    }
  }
}
```

### Configuration in `composer.json`

```json
{
  "extra": {
    "commitlint-php": {
      "rules": {
        "type": {
          "allowed": ["feat", "fix", "docs", "test", "chore"]
        }
      }
    }
  }
}
```

## 📝 Commit Message Format

This package enforces the [Conventional Commits](https://conventionalcommits.org/) specification:

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Examples

```bash
# Simple commit
feat: add user registration

# With scope
feat(auth): add JWT token validation

# With body and footer
feat(api): add user endpoints

Add comprehensive user management endpoints including:
- GET /api/users
- POST /api/users
- PUT /api/users/{id}
- DELETE /api/users/{id}

Closes #123

# Breaking change
feat(api)!: redesign user authentication

BREAKING CHANGE: The authentication API has been completely redesigned.
All existing tokens will be invalidated.
```

### Default Commit Types

- `feat` - New features
- `fix` - Bug fixes
- `docs` - Documentation changes
- `style` - Code style changes (formatting, etc)
- `refactor` - Code refactoring
- `perf` - Performance improvements
- `test` - Adding or updating tests
- `chore` - Maintenance tasks
- `ci` - CI/CD changes
- `build` - Build system changes
- `revert` - Reverting previous commits

### Special Commit Types (Auto-Skip Validation)

The following commit types automatically skip validation:
- Merge commits - `Merge branch "feature" into main`
- Revert commits - `Revert "feat: add user authentication"`
- Initial commits - `Initial commit`
- Fixup commits - `fixup! feat: add user authentication`

## 🛠️ Development

### Running Tests

```bash
# Run all tests
composer test

# With coverage
composer test:coverage
```

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Inspired by [husky](https://github.com/typicode/husky) and [commitlint](https://github.com/conventional-changelog/commitlint)
- Inspired by [dev-kraken/php-commitlint](https://github.com/dev-kraken/php-commitlint)
- Built with [Symfony Console](https://symfony.com/doc/current/components/console.html)

## Author

**chumam2050** - [choerulumam2050@gmail.com](mailto:choerulumam2050@gmail.com)

