# Usage Guide

## Installation

```bash
composer require --dev choerulumam/commitlint-php
```

## Quick Start

### 1. Install Git Hooks

```bash
vendor/bin/commitlint install
```

This will:
- Create Git hooks in `.git/hooks/`
- Generate a default `.commitlintrc.json` configuration file
- Enable commit message validation

### 2. Make Your First Commit

Try making a commit with a conventional commit message:

```bash
git add .
git commit -m "feat: add user authentication"
```

✅ **Valid!** This commit follows the conventional commits format.

Now try an invalid commit:

```bash
git commit -m "added new feature"
```

❌ **Invalid!** This will be rejected because it doesn't follow the format.

## Configuration

### Using `.commitlintrc.json`

Create or edit `.commitlintrc.json` in your project root:

```json
{
  "rules": {
    "type": {
      "required": true,
      "allowed": ["feat", "fix", "docs", "chore"]
    },
    "subject": {
      "max_length": 50
    }
  }
}
```

### Using `composer.json`

Alternatively, configure in your `composer.json`:

```json
{
  "extra": {
    "commitlint-php": {
      "rules": {
        "type": {
          "allowed": ["feat", "fix", "docs", "test"]
        }
      }
    }
  }
}
```

## Commit Message Format

### Basic Format

```
<type>: <description>
```

Examples:
```
feat: add login page
fix: resolve authentication bug
docs: update README
```

### With Scope

```
<type>(<scope>): <description>
```

Examples:
```
feat(auth): add JWT authentication
fix(ui): resolve button alignment issue
test(api): add endpoint tests
```

### With Body and Footer

```
<type>(<scope>): <description>

<body>

<footer>
```

Example:
```
feat(api): add user endpoints

Add comprehensive user management endpoints including:
- GET /api/users
- POST /api/users
- PUT /api/users/{id}
- DELETE /api/users/{id}

Closes #123
```

### Breaking Changes

Mark breaking changes with `!` after type/scope or in footer:

```
feat(api)!: redesign authentication

BREAKING CHANGE: The authentication API has been completely redesigned.
All existing tokens will be invalidated.
```

## Configuration Options

### Type Rules

```json
{
  "rules": {
    "type": {
      "required": true,
      "allowed": ["feat", "fix", "docs", "style", "refactor", "perf", "test", "chore"]
    }
  }
}
```

- `required`: Whether type is mandatory
- `allowed`: List of allowed commit types

### Scope Rules

```json
{
  "rules": {
    "scope": {
      "required": false,
      "allowed": ["auth", "ui", "api", "db"]
    }
  }
}
```

- `required`: Whether scope is mandatory
- `allowed`: List of allowed scopes (empty = any scope)

### Subject Rules

```json
{
  "rules": {
    "subject": {
      "min_length": 1,
      "max_length": 100,
      "case": "any",
      "end_with_period": false
    }
  }
}
```

- `min_length`: Minimum subject length
- `max_length`: Maximum subject length
- `case`: Subject case requirement (`"lower"`, `"upper"`, or `"any"`)
- `end_with_period`: Whether subject must/must not end with period

### Body Rules

```json
{
  "rules": {
    "body": {
      "max_line_length": 100,
      "leading_blank": true
    }
  }
}
```

- `max_line_length`: Maximum characters per line (0 = no limit)
- `leading_blank`: Require blank line after subject

### Footer Rules

```json
{
  "rules": {
    "footer": {
      "leading_blank": true
    }
  }
}
```

- `leading_blank`: Require blank line before footer

### Hook Configuration

```json
{
  "hooks": {
    "commit-msg": true,
    "pre-commit": false,
    "pre-push": false
  }
}
```

Control which Git hooks are installed:
- `commit-msg`: Validate commit messages
- `pre-commit`: Run checks before commit
- `pre-push`: Run checks before push

## Common Use Cases

### Minimal Setup (Quick Projects)

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

### Team Project (Strict Rules)

```json
{
  "rules": {
    "type": {
      "required": true,
      "allowed": ["feat", "fix", "docs", "test", "refactor"]
    },
    "scope": {
      "required": true,
      "allowed": ["auth", "api", "ui", "db", "config"]
    },
    "subject": {
      "min_length": 10,
      "max_length": 50,
      "case": "lower",
      "end_with_period": false
    },
    "body": {
      "max_line_length": 72,
      "leading_blank": true
    }
  }
}
```

### Open Source Project

```json
{
  "rules": {
    "type": {
      "allowed": [
        "feat", "fix", "docs", "style", "refactor", 
        "perf", "test", "chore", "ci", "build", "revert"
      ]
    },
    "scope": {
      "required": false,
      "allowed": []
    },
    "subject": {
      "max_length": 100,
      "case": "any"
    }
  }
}
```

## CLI Commands Reference

### Install

```bash
vendor/bin/commitlint install [options]
```

Options:
- `--force, -f`: Overwrite existing hooks
- `--skip-config`: Don't create config file

### Validate

```bash
vendor/bin/commitlint validate [message] [options]
```

Options:
- `--file, -f <path>`: Read message from file
- `--quiet, -q`: Only show exit code

Examples:
```bash
# Validate specific message
vendor/bin/commitlint validate "feat: add login"

# Validate from file
vendor/bin/commitlint validate --file=commit.txt

# Validate current commit (in git hook)
vendor/bin/commitlint validate --file=.git/COMMIT_EDITMSG

# Quiet mode for scripts
vendor/bin/commitlint validate --quiet
```

### Status

```bash
vendor/bin/commitlint status
```

Shows:
- Installed hooks status
- Configuration file location
- Current configuration

### Uninstall

```bash
vendor/bin/commitlint uninstall [options]
```

Options:
- `--force, -f`: Skip confirmation

## Troubleshooting

### Hooks Not Working

1. Check if hooks are installed:
```bash
vendor/bin/commitlint status
```

2. Verify hook files exist:
```bash
ls -la .git/hooks/
```

3. Reinstall hooks:
```bash
vendor/bin/commitlint install --force
```

### Configuration Not Loading

1. Check config file exists:
```bash
cat .commitlintrc.json
```

2. Validate JSON syntax:
```bash
cat .commitlintrc.json | python -m json.tool
```

3. Check file permissions:
```bash
ls -l .commitlintrc.json
```

### Permission Issues

Make sure the binary is executable:
```bash
chmod +x vendor/bin/commitlint
```

## Best Practices

### 1. Keep Commits Atomic
One logical change per commit.

### 2. Write Clear Descriptions
```
✅ Good: feat(auth): add JWT token validation
❌ Bad: feat: stuff
```

### 3. Use Scopes Consistently
Define scopes that match your project structure.

### 4. Include Context in Body
For complex changes, explain the "why" in the body.

### 5. Reference Issues
```
fix(api): resolve rate limiting bug

The rate limiter was not properly resetting counters.

Fixes #123
```

## Integration with CI/CD

### GitHub Actions

```yaml
name: Validate Commits
on: [push, pull_request]

jobs:
  commitlint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - name: Install dependencies
        run: composer install
      - name: Validate commit message
        run: vendor/bin/commitlint validate "${{ github.event.head_commit.message }}"
```

### GitLab CI

```yaml
commitlint:
  stage: validate
  script:
    - composer install
    - vendor/bin/commitlint validate "$CI_COMMIT_MESSAGE"
```

## Migration from Other Tools

### From Conventional Changelog

CommitLint PHP uses the same commit format, so your existing commits are compatible.

### From Husky + Commitlint (Node.js)

Replace your Node.js setup:

1. Remove Node.js dependencies:
```bash
npm uninstall husky @commitlint/cli @commitlint/config-conventional
```

2. Install CommitLint PHP:
```bash
composer require --dev choerulumam/commitlint-php
```

3. Install hooks:
```bash
vendor/bin/commitlint install
```

Your existing `.commitlintrc.json` configuration should work with minor adjustments.
