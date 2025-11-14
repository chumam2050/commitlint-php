#!/bin/bash

# Script untuk mengubah PHP version di DevContainer
# Usage: ./switch-php-version.sh 7.4

set -e

if [ -z "$1" ]; then
    echo "Usage: $0 <php-version>"
    echo "Example: $0 7.4"
    echo ""
    echo "Supported versions: 7.3, 7.4, 8.0, 8.1, 8.2, 8.3"
    exit 1
fi

PHP_VERSION=$1
COMPOSE_FILE=".devcontainer/docker-compose.yml"

# Validate PHP version
case $PHP_VERSION in
    7.3|7.4|8.0|8.1|8.2|8.3)
        echo "✓ Valid PHP version: $PHP_VERSION"
        ;;
    *)
        echo "✗ Invalid PHP version: $PHP_VERSION"
        echo "Supported versions: 7.3, 7.4, 8.0, 8.1, 8.2, 8.3"
        exit 1
        ;;
esac

# Check if file exists
if [ ! -f "$COMPOSE_FILE" ]; then
    echo "✗ Error: $COMPOSE_FILE not found"
    exit 1
fi

# Update PHP version in docker-compose.yml
echo "Updating PHP version to $PHP_VERSION..."
sed -i "s/PHP_VERSION: \"[0-9.]*\"/PHP_VERSION: \"$PHP_VERSION\"/" "$COMPOSE_FILE"

echo "✓ Updated $COMPOSE_FILE"
echo ""
echo "Next steps:"
echo "1. Rebuild DevContainer:"
echo "   - Press F1 in VS Code"
echo "   - Select 'Dev Containers: Rebuild Container'"
echo ""
echo "2. Or rebuild manually:"
echo "   docker-compose -f .devcontainer/docker-compose.yml build"
