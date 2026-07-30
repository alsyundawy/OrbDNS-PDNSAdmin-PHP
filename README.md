# OrbDNS PDNSAdmin PHP

<p align="center">
  <img src="https://raw.githubusercontent.com/alsyundawy/OrbDNS-PDNSAdmin-PHP/main/public/assets/img/logo.png" alt="OrbDNS PDNSAdmin Logo" width="120" error="this.style.display='none'">
</p>

<p align="center">
  <strong>A Modern, Lightweight, & Secure PowerDNS Management Web Interface</strong>
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/php-%3E%3D%208.1-777bb4.svg" alt="PHP Version"></a>
  <a href="https://powerdns.com"><img src="https://img.shields.io/badge/PowerDNS-API-red.svg" alt="PowerDNS"></a>
  <a href="https://bootstrapget.com"><img src="https://img.shields.io/badge/Bootstrap-5.3-7952b3.svg" alt="Bootstrap 5"></a>
</p>

---

## 🌟 Overview

**OrbDNS PDNSAdmin** is a high-performance, lightweight PHP 8.1+ Web Interface designed to simplify PowerDNS Authoritative Server administration. Built with pure PHP MVC architecture, Bootstrap 5 dark theme, and robust security practices, it provides complete control over DNS zones, activity audit logging, rate limiting, and 2FA authentication without the bloat of traditional heavy web frameworks.

---

## ✨ Key Features

- 🔐 **Two-Factor Authentication (2FA TOTP)**: Google Authenticator / Authy support with QR code initialization.
- ⚡ **High-Performance Caching**: APCu & Redis caching layer for fast DNS zone listings and query statistics.
- 🛡️ **Advanced Security & Hardening**:
  - CSRF Token Protection on all state-changing endpoints.
  - Strict Content Security Policy (CSP) with dynamic Nonce generation.
  - Built-in Rate Limiter for Login & API endpoints to prevent brute-force attacks.
  - Robust exception handling with dedicated error domains.
- 📊 **Activity & Audit Logging**: Real-time logging of zone creations, user logins, rate limits, and 2FA verifications with CSV export capability.
- 🌐 **Granular Access Control**: Role-based access control (Admin / User) and per-zone permissions.
- 🚀 **RESTful API Endpoint**: Integrated `/api/status` and `/api/zones` for external health monitoring.

---

## 🏗️ Architecture & Stack

| Layer | Technology |
| --- | --- |
| **Backend Language** | PHP 8.1+ (Strict Types Enabled) |
| **Architecture** | Custom MVC (Model-View-Controller) |
| **DNS Server** | PowerDNS Authoritative Server HTTP API |
| **Database** | MySQL / MariaDB (PDO) |
| **Cache & Limiters** | APCu / Redis |
| **Frontend UI** | Bootstrap 5, Dark Mode, jQuery |
| **Authentication** | Native Sessions + TOTP (`robthree/twofactorauth`) |

---

## 🚀 Quick Start

### 1. Requirements

- **PHP**: `^8.1` (Required extensions: `pdo_mysql`, `curl`, `mbstring`, `json`, optional: `apcu` or `redis`)
- **Web Server**: Nginx or Apache
- **Database**: MySQL 8.0+ / MariaDB 10.5+
- **PowerDNS**: PowerDNS Authoritative Server with `api=yes` enabled in `pdns.conf`

### 2. Installation

Clone the repository and install Composer dependencies:

```bash
git clone https://github.com/alsyundawy/OrbDNS-PDNSAdmin-PHP.git
cd OrbDNS-PDNSAdmin-PHP
composer install --no-dev --optimize-autoloader
```

Configure your environment settings in `.env`:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-pdns-admin.domain.com

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=pdns_admin
DB_USER=pdns_user
DB_PASS=your_secure_password

PDNS_API_URL=http://127.0.0.1:8081
PDNS_API_KEY=your_powerdns_api_key
```

Import database migrations:

```bash
mysql -u pdns_user -p pdns_admin < database/migrations/001_create_users.sql
mysql -u pdns_user -p pdns_admin < database/migrations/002_create_zone_permissions.sql
mysql -u pdns_user -p pdns_admin < database/migrations/003_create_activity_logs.sql
mysql -u pdns_user -p pdns_admin < database/seeders/admin_user.sql
```

> 📖 For a detailed step-by-step installation guide on Debian / Ubuntu systems, please refer to [INSTALL.md](INSTALL.md).

---

## 📁 Directory Structure

```text
OrbDNS-PDNSAdmin-PHP/
├── app/
│   ├── Config/           # Application & Security Configuration
│   ├── Controllers/      # Request Handlers & View Loaders
│   ├── Core/             # Framework Core (Router, Auth, Cache, Session, Validator)
│   ├── Exceptions/       # Domain-Specific Exception Classes
│   ├── Middleware/       # CSRF, Auth, & Role Middlewares
│   ├── Models/           # Database Models (User, ActivityLog, ZonePermission)
│   ├── Services/         # External Services (PowerDNS Client, TOTP)
│   └── Views/            # HTML Views & Layout Templates
├── database/
│   ├── migrations/       # SQL Database Schemas
│   └── seeders/          # Initial Administrator Seeder
├── public/               # Web Root (index.php, CSS, JS Assets)
├── .env.example          # Environment Variables Template
├── .mega-linter.yml      # CI/CD MegaLinter Configuration
├── phpcs.xml             # PSR-12 Standard Ruleset
├── phpstan.neon.dist     # PHPStan Analysis Configuration
├── psalm.xml             # Psalm Static Analysis Configuration
├── INSTALL.md            # Installation & Setup Guide
└── README.md             # Project Documentation
```

---

## 🛡️ Security & Vulnerability Reporting

Security is a primary focus of OrbDNS PDNSAdmin. If you discover a potential vulnerability, please review our [SECURITY.md](SECURITY.md) for instructions on how to report it responsibly.

---

## 📝 License

This project is open-source software licensed under the [MIT License](LICENSE).
