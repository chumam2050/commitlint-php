# Quick Start Guide - CommitLint PHP

## 🚀 Memulai Development

### Opsi 1: Menggunakan DevContainer (Recommended)
```bash
# 1. Buka VS Code
code .

# 2. Press F1 → ketik: "Dev Containers: Reopen in Container"
# 3. Tunggu build selesai (auto install dependencies)
# 4. Mulai coding!
```

### Opsi 2: Manual Setup
```bash
composer install
composer test
```

## 🔧 Mengubah PHP Version

### Method 1: Script Helper
```bash
./switch-php-version.sh 7.4
```

### Method 2: Manual
Edit `.devcontainer/docker-compose.yml`:
```yaml
PHP_VERSION: "7.4"  # Ubah disini
```

Kemudian rebuild: `F1` → `Dev Containers: Rebuild Container`

## ✅ Testing

```bash
# Run all tests
composer test

# With coverage
composer test:coverage

# Test specific file
vendor/bin/phpunit tests/ExampleTest.php
```

## 📋 Supported PHP Versions

| Version | Status |
|---------|--------|
| 7.3 | ✅ |
| 7.4 | ✅ |
| 8.0 | ✅ |
| 8.1 | ✅ (Default) |
| 8.2 | ✅ |
| 8.3 | ✅ |

## 📦 File Structure

```
.
├── .devcontainer/           # DevContainer configuration
│   ├── devcontainer.json    # VS Code settings
│   ├── docker-compose.yml   # Docker setup (change PHP version here)
│   ├── Dockerfile           # PHP environment
│   └── README.md            # DevContainer docs
├── .github/
│   └── workflows/
│       └── tests.yml        # CI/CD for all PHP versions
├── src/                     # Source code
├── tests/                   # Test files
├── composer.json            # Dependencies (PHP >=7.3)
├── phpunit.xml              # PHPUnit config
└── COMPATIBILITY.md         # PHP version compatibility matrix
```

## 🐛 Troubleshooting

### Dependencies tidak terinstall
```bash
composer install
```

### Container tidak start
```bash
# Rebuild dari scratch
F1 → "Dev Containers: Rebuild Container Without Cache"
```

### Permission errors
Container menggunakan user `vscode` (UID 1000) - tidak perlu sudo.

## 📚 Resources

- [DevContainer Setup](.devcontainer/README.md)
- [PHP Compatibility](COMPATIBILITY.md)
- [Main README](README.md)
