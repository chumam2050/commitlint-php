# Contributing to CommitLint PHP

Thank you for considering contributing to CommitLint PHP! This document provides guidelines and instructions for contributing.

## Development Environment

### Prerequisites
- Docker Desktop
- Visual Studio Code
- Remote - Containers extension

### Setup
1. Fork and clone the repository
2. Open in VS Code
3. Press `F1` → `Dev Containers: Reopen in Container`
4. Wait for container to build and dependencies to install

## PHP Version Support

This library supports **PHP 7.3 through 8.3**. Please ensure your changes work across all supported versions.

### Testing Different PHP Versions

```bash
# Using the helper script
./switch-php-version.sh 7.4

# Or using Make
make switch-php VERSION=7.4

# Then rebuild container
# F1 → Dev Containers: Rebuild Container
```

## Coding Standards

### General Guidelines
- Follow PSR-12 coding standards
- Use type hints when possible (consider PHP 7.3 compatibility)
- Write meaningful variable and function names
- Document complex logic with comments

### EditorConfig
This project includes `.editorconfig` for consistent code style:
- 4 spaces for PHP files
- 2 spaces for YAML/JSON
- UTF-8 encoding
- LF line endings

## Testing

### Running Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test:coverage

# Using Make
make test
make coverage
```

### Writing Tests
- Place tests in `tests/` directory
- Follow naming convention: `*Test.php`
- Use descriptive test method names
- Test edge cases and error conditions

Example:
```php
<?php

namespace choerulumam\CommitlintPhp\Tests;

use PHPUnit\Framework\TestCase;

class MyFeatureTest extends TestCase
{
    public function testItDoesWhatExpected(): void
    {
        // Arrange
        $input = 'test';
        
        // Act
        $result = myFunction($input);
        
        // Assert
        $this->assertEquals('expected', $result);
    }
}
```

## Pull Request Process

1. **Create a feature branch**
   ```bash
   git checkout -b feature/my-new-feature
   ```

2. **Make your changes**
   - Write clean, documented code
   - Add/update tests
   - Ensure all tests pass

3. **Test across PHP versions**
   - Test on at least PHP 7.3, 7.4, and 8.1
   - Check GitHub Actions results

4. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: add new feature"
   ```
   
   Follow [Conventional Commits](https://www.conventionalcommits.org/):
   - `feat:` new feature
   - `fix:` bug fix
   - `docs:` documentation changes
   - `test:` test additions/changes
   - `refactor:` code refactoring
   - `chore:` maintenance tasks

5. **Push and create PR**
   ```bash
   git push origin feature/my-new-feature
   ```
   
   Then create a Pull Request on GitHub with:
   - Clear description of changes
   - References to related issues
   - Screenshots (if UI changes)

## CI/CD

GitHub Actions automatically:
- Runs tests on PHP 7.3, 7.4, 8.0, 8.1, 8.2, 8.3
- Validates composer.json
- Generates coverage report

All checks must pass before merging.

## Project Structure

```
.
├── .devcontainer/        # DevContainer configuration
├── .github/workflows/    # CI/CD workflows
├── .vscode/             # VS Code settings
├── src/                 # Source code (PSR-4)
├── tests/               # Test files (PSR-4)
├── composer.json        # Dependencies
├── phpunit.xml          # PHPUnit config
└── README.md           # Documentation
```

## Getting Help

- Check [README.md](README.md) for general info
- Review [QUICKSTART.md](QUICKSTART.md) for quick help
- See [COMPATIBILITY.md](COMPATIBILITY.md) for PHP version info
- Open an issue for questions or bugs

## Code of Conduct

- Be respectful and inclusive
- Provide constructive feedback
- Focus on the code, not the person
- Help others learn and grow

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
