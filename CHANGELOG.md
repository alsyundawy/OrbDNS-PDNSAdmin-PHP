# CHANGELOG

## 1.2.0 - 2026-07-30

### Added

- Panduan instalasi lengkap untuk sistem Debian / Ubuntu ([INSTALL.md](INSTALL.md)).
- Dokumentasi `README.md` baru yang modern, profesional, dan informatif.
- Hirarki eksepsi domain khusus (`AppException`, `DatabaseException`, `PowerDNSException`).
- Konfigurasi MegaLinter & Linter Root ([.mega-linter.yml](.mega-linter.yml), [phpstan.neon.dist](phpstan.neon.dist), [psalm.xml](psalm.xml), [phpcs.xml](phpcs.xml), [.cspell.json](.cspell.json)).

### Changed

- Penggantian `serialize()` / `unserialize()` menjadi `json_encode()` / `json_decode()` di `Cache.php` untuk mencegah celah PHP Object Injection.
- Refactoring `Router.php` untuk mengurangi *cognitive complexity* dan menegakkan aturan kurung kurawal percabangan.
- Hardening keamanan GitHub Actions workflows (`permissions: contents: read` & pembaruan `actions/cache@v4`).
- Peningkatan aksesibilitas HTML form (label `for` & input `id`) pada seluruh tampilan view.

## 1.1.0 - 2026-07-29

### Added

- Rate limiting login dan API berbasis APCu/Redis.
- Implementasi 2FA TOTP controller, service, dan view.
- Endpoint `/api/status` untuk PDNS health check.
- Validator class terpusat.
- Export activity logs ke CSV.
- Permission service untuk granular zone access.
- CSP nonce untuk inline/local scripts.
- Output buffering dan gzip response support.
- Audit log untuk failed login, rate-limit, dan failed TOTP.

### Changed

- Caching zone listing pada PowerDNS client selama 60 detik.
- Hardening session, security headers, dan response handling.
