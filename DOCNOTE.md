# DOCNOTE

## Perubahan Perilaku & Fitur Utama (v1.3.0)

- **Manajemen User Admin (`/users`)**: Administrator (Superuser) kini dapat melihat daftar user dan menambahkan user baru melalui UI web untuk mengantisipasi administrator yang lupa/kehilangan akses akun.
- **Header Navigasi Web**: Ditambahkan bar navigasi utama pada `layouts/app.php` yang memuat link cepat ke Dashboard, Zones, Users (khusus Admin), Logs (khusus Admin), dan Logout.
- **Serialisasi Cache Safe**: Driver Redis pada `Cache.php` menggunakan `json_encode()` dan `json_decode()` menggantikan `serialize()`/`unserialize()` untuk keamanan terhadap celah Object Injection.
- **Hirarki Eksepsi Domain**: Penanganan error database dan cURL PowerDNS melempar `DatabaseException` atau `PowerDNSException` (turunan `AppException`), bukan `RuntimeException` generik.
- **Konfigurasi Linter Root**: Ditambahkan file konfigurasi `.mega-linter.yml`, `phpstan.neon.dist`, `psalm.xml`, `phpcs.xml`, `.php-cs-fixer.php`, `phpstan-bootstrap.php`, dan `.cspell.json` untuk mengisolasi analisis statis hanya pada kode aplikasi (`app/`, `public/`), mengabaikan direktori `vendor/`.
- **Parameter Binding PDO Safe (`Database.php`)**: Memperbaiki `Database::query()` dengan pendeteksian tipe otomatis (`PDO::PARAM_INT`, `PDO::PARAM_BOOL`, `PDO::PARAM_NULL`, `PDO::PARAM_STR`) agar query `LIMIT ? OFFSET ?` dan named parameters berjalan sempurna tanpa error pada MySQL native prepared statements.
- **Hardening Linter IDE & Autoload Fixer (`.php-cs-fixer.php`)**: Ditambahkan pemeriksaan keberadaan kelas `\PhpCsFixer\Config` dan `\PhpCsFixer\Finder` pada `.php-cs-fixer.php` serta normalisasi inklusi `require_once` untuk menghilangkan seluruh peringatan/error pada IDE inspection.
- **Panduan Instalasi Server**: Panduan deploy lingkungan produksi Debian 11/12 dan Ubuntu 22.04/24.04 tersedia di `INSTALL.md`.
- **Hardening Cookie & Actions**: Cookie session secara otomatis mengevaluasi flag `secure` saat berjalan di atas HTTPS, dan seluruh workflow CI/CD GitHub Actions menerapkan prinsip *least-privilege permissions*.
