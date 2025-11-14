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

### PHP Version Support
Library ini compatible dengan **PHP 7.3 hingga PHP 8.x**.

DevContainer default menggunakan **PHP 8.1**, tetapi bisa diubah sesuai kebutuhan:

**Cara mengubah PHP version:**
1. Edit `.devcontainer/docker-compose.yml`
2. Ubah value `PHP_VERSION` di bagian `build.args`:
   ```yaml
   build:
     args:
       PHP_VERSION: "7.3"  # Ubah sesuai kebutuhan
   ```
3. Rebuild container: `F1` → `Dev Containers: Rebuild Container`

**Versi yang didukung:**
- PHP 7.3
- PHP 7.4
- PHP 8.0
- PHP 8.1 (default)
- PHP 8.2
- PHP 8.3

### Extensions yang Terinstall
- **Intelephense**: PHP IntelliSense
- **PHP Debug**: Xdebug integration
- **PHP Namespace Resolver**: Auto-resolve PHP namespaces
- **PHPUnit**: Run tests dari VS Code
- **GitLens**: Enhanced Git capabilities
- **EditorConfig**: Code style consistency

### Xdebug Configuration
Xdebug sudah dikonfigurasi otomatis berdasarkan PHP version:

**PHP 8.x:**
- Mode: debug, coverage
- Start with request: yes
- Client host: host.docker.internal

**PHP 7.x:**
- Remote enable: 1
- Remote autostart: 1
- Remote host: host.docker.internal

## Customization

### Menambah PHP Extension
Edit `.devcontainer/Dockerfile`:
```dockerfile
RUN docker-php-ext-install pdo pdo_mysql
```

### Menambah Database (MySQL)
Uncomment bagian database di `.devcontainer/docker-compose.yml`

### Mengubah PHP Version
Edit `.devcontainer/docker-compose.yml`:
```yaml
build:
  args:
    PHP_VERSION: "7.4"  # Ganti sesuai kebutuhan (7.3, 7.4, 8.0, 8.1, 8.2, 8.3)
```
Kemudian rebuild: `F1` → `Dev Containers: Rebuild Container`

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
