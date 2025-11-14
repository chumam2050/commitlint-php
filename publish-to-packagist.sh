#!/bin/bash

# Script untuk membantu publish package ke Packagist
# Usage: ./publish-to-packagist.sh [version]

set -e

VERSION=${1:-"1.0.0"}

echo "🚀 Publishing commitlint-php v${VERSION} to Packagist"
echo ""

# Validasi composer.json
echo "📋 Validating composer.json..."
composer validate --strict
echo "✅ composer.json is valid"
echo ""

# Jalankan tests
echo "🧪 Running tests..."
composer test
echo "✅ All tests passed"
echo ""

# Check git status
if [[ -n $(git status -s) ]]; then
    echo "⚠️  Warning: You have uncommitted changes"
    git status -s
    echo ""
    read -p "Do you want to commit these changes? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git add .
        read -p "Enter commit message: " commit_msg
        git commit -m "$commit_msg"
    else
        echo "❌ Please commit or stash your changes first"
        exit 1
    fi
fi

# Push ke GitHub
echo "📤 Pushing to GitHub..."
git push origin main
echo "✅ Pushed to GitHub"
echo ""

# Create and push tag
echo "🏷️  Creating tag v${VERSION}..."
if git rev-parse "v${VERSION}" >/dev/null 2>&1; then
    echo "⚠️  Tag v${VERSION} already exists"
    read -p "Do you want to delete and recreate it? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git tag -d "v${VERSION}"
        git push origin ":refs/tags/v${VERSION}" 2>/dev/null || true
    else
        echo "❌ Aborted"
        exit 1
    fi
fi

git tag -a "v${VERSION}" -m "Release version ${VERSION}"
git push origin "v${VERSION}"
echo "✅ Tag v${VERSION} created and pushed"
echo ""

# Informasi selanjutnya
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📦 Next Steps:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "1. Go to: https://packagist.org/"
echo "2. Login with your GitHub account"
echo "3. Click 'Submit' button (top right)"
echo "4. Enter your repository URL:"
echo "   https://github.com/chumam2050/commitlint-php"
echo "5. Click 'Check' and then 'Submit'"
echo ""
echo "After submission:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "• Setup GitHub webhook for auto-updates"
echo "• Your package will be available at:"
echo "  https://packagist.org/packages/choerulumam/commitlint-php"
echo ""
echo "Installation command for users:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  composer require --dev choerulumam/commitlint-php"
echo ""
echo "✨ Done! Package is ready for Packagist submission."
