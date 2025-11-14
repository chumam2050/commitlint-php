# PHP Compatibility Matrix

## Supported PHP Versions

| PHP Version | Status | PHPUnit Version | Xdebug Config |
|-------------|--------|-----------------|---------------|
| 7.3         | ✅ Supported | 8.x / 9.x | Legacy (remote_*) |
| 7.4         | ✅ Supported | 8.x / 9.x | Legacy (remote_*) |
| 8.0         | ✅ Supported | 9.x | Modern (mode) |
| 8.1         | ✅ Supported (Default) | 9.x | Modern (mode) |
| 8.2         | ✅ Supported | 9.x | Modern (mode) |
| 8.3         | ✅ Supported | 9.x | Modern (mode) |

## Testing Across Versions

### Local Testing with DevContainer

1. Edit `.devcontainer/docker-compose.yml`:
```yaml
build:
  args:
    PHP_VERSION: "7.3"  # Change to desired version
```

2. Rebuild container:
   - Press `F1`
   - Select `Dev Containers: Rebuild Container`

3. Run tests:
```bash
composer test
```

### CI/CD Testing

GitHub Actions workflow (`.github/workflows/tests.yml`) automatically tests against all supported PHP versions.

## Feature Compatibility

### PHP 7.3 - 7.4
- Basic OOP features
- Namespaces
- Type hints (scalar, return types)
- Xdebug 2.x configuration (remote_enable)

### PHP 8.0+
- Named arguments
- Union types
- Match expressions
- Xdebug 3.x configuration (mode)

## Dependencies

### Composer
```json
{
  "require": {
    "php": ">=7.3"
  },
  "require-dev": {
    "phpunit/phpunit": "^9.5 || ^8.0"
  }
}
```

- PHPUnit 9.5: Recommended for PHP 7.3+
- PHPUnit 8.0: Fallback for older setups

## Troubleshooting

### PHPUnit version conflicts
If you encounter PHPUnit version issues:

```bash
# Clear composer cache
composer clear-cache

# Remove vendor
rm -rf vendor composer.lock

# Reinstall with specific PHP version
composer install
```

### Xdebug configuration
The DevContainer automatically configures Xdebug based on PHP version:
- PHP 7.x: Uses legacy `remote_*` settings
- PHP 8.x: Uses modern `mode` settings

No manual configuration needed!
