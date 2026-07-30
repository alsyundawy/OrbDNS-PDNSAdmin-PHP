# DOCNOTE

## Perubahan Perilaku & Fitur Utama

- **Serialisasi Cache Safe**: Driver Redis pada `Cache.php` kini menggunakan `json_encode()` dan `json_decode()` menggantikan `serialize()`/`unserialize()` untuk keamanan terhadap celah Object Injection.
- **Hirarki Eksepsi Domain**: Penanganan error database dan cURL PowerDNS kini melempar `DatabaseException` atau `PowerDNSException` (turunan `AppException`), bukan `RuntimeException` generik.
- **Konfigurasi Linter Root**: Ditambahkan file konfigurasi `.mega-linter.yml`, `phpstan.neon.dist`, `psalm.xml`, `phpcs.xml`, dan `.cspell.json` untuk mengisolasi analisis statis hanya pada kode aplikasi (`app/`, `public/`), mengabaikan direktori `vendor/`.
- **Panduan Instalasi Server**: Panduan deploy lingkungan produksi Debian 11/12 dan Ubuntu 22.04/24.04 ditambahkan pada `INSTALL.md`.
- **Hardening Cookie & Actions**: Cookie session secara otomatis mengevaluasi flag `secure` saat berjalan di atas HTTPS, dan seluruh workflow CI/CD GitHub Actions menerapkan prinsip *least-privilege permissions*.
