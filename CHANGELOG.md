# CHANGELOG

## Version 1.3.0 (2026-07-30)

### Added in 1.3.0

- Fitur Manajemen User Admin/Superuser (`/users`, `/users/create`, `/users/store`) untuk membuat user baru dan memulihkan akses administrator.
- Header Navigasi Utama pada `app.php` untuk mempermudah navigasi antara Dashboard, Zones, Users, Logs, dan Logout.
- Metode `all()`, `exists()`, dan `create()` pada `User` model.
- Bootstrap static analysis (`phpstan-bootstrap.php`) dan konfigurasi `.php-cs-fixer.php`.

### Changed in 1.3.0

- Pembaruan konstanta versi aplikasi `APP_VERSION` menjadi `1.3.0`.
- Audit komprehensif seluruh kode PHP, route protection (`role:admin`), sanitasi input, dan penanganan CSRF.
- Perbaikan binding parameter `Database::query()` dengan tipe data eksplisit (`PDO::PARAM_INT`, `PDO::PARAM_BOOL`, `PDO::PARAM_NULL`, `PDO::PARAM_STR`) untuk query `LIMIT` dan `OFFSET`.
- Pembenahan `.php-cs-fixer.php` dengan pengecekan `class_exists` untuk mencegah error undefined type pada IDE.
- Kepatuhan 100% terhadap `phpcs` (PSR-12), `phpstan` (Level 5), `psalm` (Level 4), `phplint`, `php-cs-fixer`, dan IDE inspection rules.

## Version 1.2.0 (2026-07-30)

### Added in 1.2.0

- Panduan instalasi lengkap untuk sistem Debian / Ubuntu ([INSTALL.md](INSTALL.md)).
- Dokumentasi `README.md` baru yang modern, profesional, dan informatif.
- Hirarki eksepsi domain khusus (`AppException`, `DatabaseException`, `PowerDNSException`).
- Konfigurasi MegaLinter & Linter Root ([.mega-linter.yml](.mega-linter.yml), [phpstan.neon.dist](phpstan.neon.dist), [psalm.xml](psalm.xml), [phpcs.xml](phpcs.xml), [.cspell.json](.cspell.json)).

### Changed in 1.2.0

- Penggantian `serialize()` / `unserialize()` menjadi `json_encode()` / `json_decode()` di `Cache.php` untuk mencegah celah PHP Object Injection.
- Refactoring `Router.php` untuk mengurangi *cognitive complexity* dan menegakkan aturan kurung kurawal percabangan.
- Hardening keamanan GitHub Actions workflows (`permissions: contents: read` & pembaruan `actions/cache@v4`).
- Peningkatan aksesibilitas HTML form (label `for` & input `id`) pada seluruh tampilan view.

## Version 1.1.0 (2026-07-29)

### Added in 1.1.0

- Rate limiting login dan API berbasis APCu/Redis.
- Implementasi 2FA TOTP controller, service, dan view.
- Endpoint `/api/status` untuk PDNS health check.
- Validator class terpusat.
- Export activity logs ke CSV.
- Permission service untuk granular zone access.
- CSP nonce untuk inline/local scripts.
- Output buffering dan gzip response support.
- Audit log untuk failed login, rate-limit, dan failed TOTP.

### Changed in 1.1.0

- Caching zone listing pada PowerDNS client selama 60 detik.
- Hardening session, security headers, dan response handling.
