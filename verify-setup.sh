#!/bin/bash

# Script untuk verifikasi setup DevContainer
# Jalankan di dalam container: ./verify-setup.sh

set -e

echo "╔════════════════════════════════════════════════════╗"
echo "║  CommitLint PHP - DevContainer Verification       ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""

# Check PHP version
echo "✓ Checking PHP version..."
php -v | head -n 1
echo ""

# Check Composer
echo "✓ Checking Composer..."
composer --version
echo ""

# Check Xdebug
echo "✓ Checking Xdebug..."
php -v | grep -i xdebug || echo "⚠ Xdebug not detected"
echo ""

# Check required extensions
echo "✓ Checking PHP extensions..."
php -m | grep -E '^(zip|json|mbstring)' || true
echo ""

# Check if vendor exists
echo "✓ Checking vendor directory..."
if [ -d "vendor" ]; then
    echo "✓ Vendor directory exists"
else
    echo "⚠ Vendor directory not found. Run: composer install"
fi
echo ""

# Check PHPUnit
echo "✓ Checking PHPUnit..."
if [ -f "vendor/bin/phpunit" ]; then
    vendor/bin/phpunit --version
else
    echo "⚠ PHPUnit not found. Run: composer install"
fi
echo ""

# Validate composer.json
echo "✓ Validating composer.json..."
composer validate --strict 2>&1 | grep -E '(is valid|OK)' || true
echo ""

# Check file permissions
echo "✓ Checking file permissions..."
if [ -w "composer.json" ]; then
    echo "✓ Write permissions OK"
else
    echo "✗ No write permissions"
fi
echo ""

# PHP version compatibility check
echo "✓ PHP Version Compatibility Check..."
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
MAJOR=$(echo $PHP_VERSION | cut -d. -f1)
MINOR=$(echo $PHP_VERSION | cut -d. -f2)

if [ "$MAJOR" -eq 7 ] && [ "$MINOR" -ge 3 ]; then
    echo "✓ PHP $PHP_VERSION is supported (7.3+)"
elif [ "$MAJOR" -ge 8 ]; then
    echo "✓ PHP $PHP_VERSION is supported (8.x)"
else
    echo "✗ PHP $PHP_VERSION is NOT supported (require >=7.3)"
fi
echo ""

# Summary
echo "╔════════════════════════════════════════════════════╗"
echo "║  Verification Complete                             ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""
echo "Ready to develop! Try:"
echo "  composer test         # Run tests"
echo "  composer test:coverage # Run tests with coverage"
echo "  make help             # Show available commands"
echo ""
