# commitlint-php

Commitlint For PHP - A library for validating commit messages following conventional commit standards.

## Requirements

- PHP >= 7.3
- Composer

## Installation

```bash
composer require choerulumam/commitlint-php
```

## Development

### Using DevContainer (Recommended)

This project includes a complete DevContainer setup for consistent development environment.

**Requirements:**
- Docker Desktop
- Visual Studio Code
- Remote - Containers extension

**Quick Start:**
1. Open project in VS Code
2. Press `F1` → `Dev Containers: Reopen in Container`
3. Wait for container to build and dependencies to install
4. Start coding!

**PHP Version:**
- Default: PHP 8.1
- Supports: PHP 7.3, 7.4, 8.0, 8.1, 8.2, 8.3
- To change version, edit `PHP_VERSION` in `.devcontainer/docker-compose.yml`

See [DevContainer README](.devcontainer/README.md) for more details.

### Manual Setup

```bash
# Install dependencies
composer install

# Run tests
composer test

# Run tests with coverage
composer test:coverage
```

## Testing

```bash
# Run all tests
composer test

# With coverage
composer test:coverage
```

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details.

## Documentation

- 📖 [Quick Start Guide](QUICKSTART.md) - Get started quickly
- 🐳 [DevContainer Setup](.devcontainer/README.md) - Development environment
- 🔧 [PHP Compatibility](COMPATIBILITY.md) - Version compatibility matrix
- 📝 [Changelog](CHANGELOG.md) - Release history
- 🤝 [Contributing](CONTRIBUTING.md) - How to contribute

## Author

**chumam2050** - [choerulumam2050@gmail.com](mailto:choerulumam2050@gmail.com)
