# Panduan Instalasi OrbDNS PDNSAdmin — Debian / Ubuntu

Panduan ini menjelaskan langkah-langkah lengkap instalasi **OrbDNS PDNSAdmin PHP** pada sistem operasi **Debian 11 (Bullseye)**, **Debian 12 (Bookworm)**, atau **Ubuntu 22.04 / 24.04 LTS**.

---

## 📋 Persyaratan Sistem

- **Sistem Operasi**: Debian 11 / Debian 12 / Ubuntu 20.04+
- **Web Server**: Nginx (direkomendasikan) atau Apache2
- **PHP**: PHP 8.1 / 8.2 / 8.3 dengan PHP-FPM
- **Database**: MariaDB 10.5+ atau MySQL 8.0+
- **DNS Engine**: PowerDNS Authoritative Server dengan HTTP API aktif
- **Package Manager**: Composer 2.x

---

## 🛠️ Langkah 1: Update Sistem & Install Dependensi Utama

Buka terminal server Debian Anda dan jalankan perintah berikut:

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git unzip nginx mariadb-server \
  php8.2-fpm php8.2-cli php8.2-mysql php8.2-curl \
  php8.2-mbstring php8.2-xml php8.2-apcu
```

> 💡 *Catatan: Jika versi PHP default di Debian Anda adalah `php8.1` atau `php8.3`, sesuaikan penamaan paket di atas (`php8.1-fpm` / `php8.3-fpm`).*

---

## 📦 Langkah 2: Install Composer

Install Composer secara global untuk mengelola dependensi PHP:

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

---

## 🗄️ Langkah 3: Konfigurasi Database MariaDB

1. Amankan instalasi MariaDB:

```bash
sudo mysql_secure_installation
```

1. Masuk ke console MariaDB untuk membuat database dan user:

```sql
sudo mysql -u root -p
```

Jalankan perintah SQL berikut:

```sql
CREATE DATABASE pdns_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pdns_user'@'localhost' IDENTIFIED BY 'PasswordSuperAman123!';
GRANT ALL PRIVILEGES ON pdns_admin.* TO 'pdns_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## ⚡ Langkah 4: Aktifkan PowerDNS HTTP API

Pastikan PowerDNS Authoritative Server sudah terinstall. Buka file konfigurasi `pdns.conf`:

```bash
sudo nano /etc/powerdns/pdns.conf
```

Tambahkan atau sesuaikan baris konfigurasi API berikut:

```ini
api=yes
api-key=SecretPowerDNSApiKey123!
webserver=yes
webserver-address=127.0.0.1
webserver-port=8081
webserver-allow-from=127.0.0.1
```

Simpan file, lalu restart layanan PowerDNS:

```bash
sudo systemctl restart pdns
```

Uji koneksi HTTP API PowerDNS:

```bash
curl -v -H 'X-API-Key: SecretPowerDNSApiKey123!' http://127.0.0.1:8081/api/v1/servers/localhost
```

---

## 📂 Langkah 5: Clone Repository & Dependency Setup

1. Clone project ke direktori `/var/www/html/OrbDNS-PDNSAdmin-PHP`:

```bash
cd /var/www/html
sudo git clone https://github.com/alsyundawy/OrbDNS-PDNSAdmin-PHP.git
cd OrbDNS-PDNSAdmin-PHP
```

1. Install dependensi Composer tanpa dev package:

```bash
sudo composer install --no-dev --optimize-autoloader
```

1. Buat file `.env` dari template:

```bash
sudo cp .env.example .env
sudo nano .env
```

Sesuaikan nilai variabel lingkungan di `.env`:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pdns.domainanda.com

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=pdns_admin
DB_USER=pdns_user
DB_PASS=PasswordSuperAman123!

PDNS_API_URL=http://127.0.0.1:8081
PDNS_API_KEY=SecretPowerDNSApiKey123!
PDNS_SERVER=localhost

CACHE_DRIVER=apcu
```

---

## 🗃️ Langkah 6: Import Schema & Data Awal Database

Jalankan perintah SQL migration untuk membuat tabel-tabel yang diperlukan:

```bash
mysql -u pdns_user -p'PasswordSuperAman123!' pdns_admin < database/migrations/001_create_users.sql
mysql -u pdns_user -p'PasswordSuperAman123!' pdns_admin < database/migrations/002_create_zone_permissions.sql
mysql -u pdns_user -p'PasswordSuperAman123!' pdns_admin < database/migrations/003_create_activity_logs.sql
mysql -u pdns_user -p'PasswordSuperAman123!' pdns_admin < database/seeders/admin_user.sql
```

---

## 🔑 Langkah 7: Atur Hak Akses File

Berikan hak milik direktori web kepada user webserver `www-data`:

```bash
sudo chown -R www-data:www-data /var/www/html/OrbDNS-PDNSAdmin-PHP
sudo chmod -R 755 /var/www/html/OrbDNS-PDNSAdmin-PHP
```

---

## 🌐 Langkah 8: Konfigurasi Nginx Web Server

Buat file vhost Nginx baru di `/etc/nginx/sites-available/pdns-admin`:

```bash
sudo nano /etc/nginx/sites-available/pdns-admin
```

Isikan konfigurasi berikut:

```nginx
server {
    listen 80;
    server_name pdns.domainanda.com;
    root /var/www/html/OrbDNS-PDNSAdmin-PHP/public;
    index index.php;

    access_log /var/log/nginx/pdns_admin_access.log;
    error_log /var/log/nginx/pdns_admin_error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.(env|git) {
        deny all;
    }
}
```

Aktifkan konfigurasi Nginx dan uji sintaks:

```bash
sudo ln -s /etc/nginx/sites-available/pdns-admin /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔒 Langkah 9: SSL Certificate (Let's Encrypt / Certbot)

Sangat direkomendasikan menggunakan HTTPS di lingkungan produksi:

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d pdns.domainanda.com
```

---

## 🎉 Langkah 10: Verifikasi & Login Pertama

1. Buka browser dan kunjungi `https://pdns.domainanda.com/login`.
1. Kredensial default bawaan seeder:
   - **Username**: `admin`
   - **Password**: `admin123`
1. **PENTING**: Segera ganti password admin default dan aktifkan 2FA TOTP demi keamanan server Anda.
