# Publishing to Packagist

Panduan lengkap untuk mempublikasikan package `commitlint-php` ke Packagist.

## Prerequisites

- [x] Repository GitHub sudah dibuat: `chumam2050/commitlint-php`
- [x] Kode sudah siap dan tested
- [x] `composer.json` sudah valid
- [ ] Akun Packagist (akan dibuat saat publish)

## Quick Publish

### Otomatis dengan Script

```bash
./publish-to-packagist.sh 1.0.0
```

Script ini akan:
1. Validate `composer.json`
2. Jalankan semua tests
3. Commit perubahan (jika ada)
4. Push ke GitHub
5. Buat dan push tag `v1.0.0`
6. Tampilkan instruksi untuk submit ke Packagist

### Manual Steps

#### 1. Pastikan Semua Siap

```bash
# Validate composer.json
composer validate --strict

# Run tests
composer test

# Check status
git status
```

#### 2. Commit dan Push ke GitHub

```bash
git add .
git commit -m "chore: prepare for v1.0.0 release"
git push origin main
```

#### 3. Buat Version Tag

```bash
# Buat tag
git tag -a v1.0.0 -m "Release version 1.0.0"

# Push tag ke GitHub
git push origin v1.0.0
```

#### 4. Submit ke Packagist

1. Buka https://packagist.org/
2. Login/Register dengan akun GitHub
3. Klik tombol **"Submit"** di pojok kanan atas
4. Masukkan repository URL:
   ```
   https://github.com/chumam2050/commitlint-php
   ```
5. Klik **"Check"**
6. Jika validasi berhasil, klik **"Submit"**

## Setelah Published

### Setup Auto-Update (Recommended)

Agar Packagist otomatis update saat ada push baru:

1. Di halaman package Packagist Anda, klik **"Show API Token"**
2. Copy webhook URL dan token
3. Di GitHub repository:
   - Settings → Webhooks → Add webhook
   - Paste URL dari Packagist
   - Content type: `application/json`
   - Secret: paste token dari Packagist
   - Events: pilih "Just the push event"
   - Klik "Add webhook"

### Verifikasi Package

```bash
# Di project lain, coba install
composer require --dev choerulumam/commitlint-php

# Atau dengan versi spesifik
composer require --dev choerulumam/commitlint-php:^1.0
```

### Monitor Package

- Package page: https://packagist.org/packages/choerulumam/commitlint-php
- Stats & downloads: akan muncul di halaman package
- Badge untuk README: Packagist menyediakan badge otomatis

## Updating Package

### Untuk Bug Fixes (Patch Version)

```bash
./publish-to-packagist.sh 1.0.1
```

### Untuk New Features (Minor Version)

```bash
./publish-to-packagist.sh 1.1.0
```

### Untuk Breaking Changes (Major Version)

```bash
./publish-to-packagist.sh 2.0.0
```

## Semantic Versioning

Ikuti [Semantic Versioning](https://semver.org/):

- **MAJOR** (1.0.0 → 2.0.0): Breaking changes
- **MINOR** (1.0.0 → 1.1.0): New features, backward compatible
- **PATCH** (1.0.0 → 1.0.1): Bug fixes, backward compatible

## Badges untuk README

Setelah published, tambahkan badge Packagist ke README:

```markdown
[![Packagist Version](https://img.shields.io/packagist/v/choerulumam/commitlint-php)](https://packagist.org/packages/choerulumam/commitlint-php)
[![Packagist Downloads](https://img.shields.io/packagist/dt/choerulumam/commitlint-php)](https://packagist.org/packages/choerulumam/commitlint-php)
[![Packagist License](https://img.shields.io/packagist/l/choerulumam/commitlint-php)](https://packagist.org/packages/choerulumam/commitlint-php)
```

## Troubleshooting

### Package tidak ditemukan

- Pastikan repository URL benar
- Cek apakah repository public
- Tunggu beberapa menit setelah submit (Packagist perlu indexing)

### Webhook tidak bekerja

- Cek webhook settings di GitHub
- Pastikan token benar
- Lihat delivery log di GitHub webhook settings

### Version tidak update

- Pastikan tag sudah di-push ke GitHub
- Check webhook atau manual update di Packagist
- Packagist update setiap 12 jam jika webhook tidak aktif

## Resources

- [Packagist Documentation](https://packagist.org/about)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Semantic Versioning](https://semver.org/)
