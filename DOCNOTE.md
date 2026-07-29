# DOCNOTE

## Perubahan Perilaku
- Login sekarang memakai rate limiting berbasis APCu/Redis, bukan session.
- 2FA TOTP diimplementasikan melalui `robthree/twofactorauth`.
- Endpoint baru `/api/status` ditambahkan untuk health check PowerDNS.
- Zone listing memakai cache selama 60 detik.
- CSP diperketat memakai nonce untuk script.
- Export audit log ke CSV tersedia di `/logs/export`.
