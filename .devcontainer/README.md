# CommitLint PHP - Dev Container Setup

## Cara Menggunakan

### Prerequisites
- Docker Desktop atau Docker Engine
- Visual Studio Code
- Extension: Remote - Containers (ms-vscode-remote.remote-containers)

### Langkah-langkah

1. **Buka project di VS Code**
   ```bash
   code /home/dev/www/commitlint-php
   ```

2. **Reopen in Container**
   - Tekan `F1` atau `Ctrl+Shift+P`
   - Ketik dan pilih: `Dev Containers: Reopen in Container`
   - Tunggu hingga container selesai dibuild dan dependencies terinstall

3. **Mulai Development**
   Container sudah siap dengan:
   - PHP 8.1 CLI
   - Composer
   - Xdebug (untuk debugging dan coverage)
   - PHPUnit
   - Git

## Struktur DevContainer

```
.devcontainer/
├── devcontainer.json    # Konfigurasi VS Code devcontainer
├── docker-compose.yml   # Docker compose configuration
└── Dockerfile          # PHP development environment
```

## Menjalankan Tests

Setelah container berjalan, jalankan test dengan:

```bash
# Run all tests
composer test

# Run with coverage
composer test:coverage
```

## Features

### Extensions yang Terinstall
- **Intelephense**: PHP IntelliSense
- **PHP Debug**: Xdebug integration
- **PHP Namespace Resolver**: Auto-resolve PHP namespaces
- **PHPUnit**: Run tests dari VS Code
- **GitLens**: Enhanced Git capabilities
- **EditorConfig**: Code style consistency

### Xdebug Configuration
Xdebug sudah dikonfigurasi dengan:
- Mode: debug, coverage
- Start with request: yes
- Client host: host.docker.internal

## Customization

### Menambah PHP Extension
Edit `.devcontainer/Dockerfile`:
```dockerfile
RUN docker-php-ext-install pdo pdo_mysql
```

### Menambah Database (MySQL)
Uncomment bagian database di `.devcontainer/docker-compose.yml`

### Mengubah PHP Version
Edit `.devcontainer/Dockerfile`:
```dockerfile
FROM php:8.2-cli  # Ganti dari 8.1 ke 8.2
```

## Troubleshooting

### Container tidak start
- Pastikan Docker berjalan
- Cek log: View > Output > Remote - Containers

### Dependencies tidak terinstall
Jalankan manual:
```bash
composer install
```

### Permission issues
Container menggunakan user `vscode` (UID 1000) untuk menghindari permission issues.
