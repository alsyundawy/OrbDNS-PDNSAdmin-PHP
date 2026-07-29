# CHANGELOG

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
