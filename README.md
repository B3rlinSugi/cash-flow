# 💰 Cash Flow Manager — Class Financial Management System

A class cash flow management system built with PHP & MySQL — rebuilt from a legacy codebase with modern security, real-time analytics, and PDF report generation. Focused on data integrity, bcrypt authentication, and an analytics dashboard for full financial visibility.

[![Live Demo](https://img.shields.io/badge/Live%20Demo-cash--flow.railway.app-4e73df?style=for-the-badge&logo=railway)](https://cash-flow-production-d733.up.railway.app)
[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)
[![Railway](https://img.shields.io/badge/Deployed-Railway-0B0D0E?style=for-the-badge&logo=railway)](https://railway.app)

---

## 📋 Table of Contents

- [Live Demo](#-live-demo)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Database Schema](#-database-schema)
- [Project Structure](#-project-structure)
- [Installation (Local)](#-installation-local)
- [Environment Variables (Railway)](#-environment-variables-railway)
- [Default Credentials](#-default-credentials)
- [Author](#-author)

---

## 🌐 Live Demo

**[https://cash-flow-production-d733.up.railway.app](https://cash-flow-production-d733.up.railway.app)**

> Login with the default credentials below to explore the dashboard.

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔒 **Secure Auth** | bcrypt password hashing, session management, login protection |
| 📊 **Analytics Dashboard** | 6-month income vs expense chart with real-time balance |
| 👥 **Member Management** | Full CRUD for organization members with level & status tracking |
| 💵 **Kas Masuk (Income)** | Record and manage cash income per member |
| ⏳ **Kas Ditunda** | Track deferred/pending payments per member |
| 💸 **Pengeluaran (Expense)** | Log organizational expenses with categorization |
| 🖨️ **PDF Export** | Generate downloadable financial reports via Dompdf |
| 🔍 **Search & Filter** | Fast transaction lookup across all modules |
| 📱 **Responsive** | Mobile-friendly layout using Bootstrap 5 |

---

## 🛠 Tech Stack

| Technology | Role |
|---|---|
| **PHP 8.0+** | Server-side logic & routing |
| **MySQL 8** | Relational database with FK constraints |
| **Bootstrap 5** | Responsive frontend framework |
| **Chart.js 4** | 6-month income/expense visualization |
| **Dompdf 3** | Server-side PDF report generation |
| **Railway** | Cloud deployment (PHP + MySQL) |

---

## 🗄 Database Schema

### Tables

```
admin          — Admin accounts (bcrypt hashed passwords)
anggota        — Organization members (nama, alamat, umur, level_kas, status)
kas            — Income records linked to anggota (FK constrained)
kas_ditunda    — Deferred/pending payment records
pengeluaran    — Expense records
```

### Relationships

```
anggota (1) ──< kas (N)
anggota (1) ──< kas_ditunda (N)
```

> All FK constraints use `ON DELETE RESTRICT` to prevent orphaned records.

---

## 📁 Project Structure

```
cash-flow/
├── anggota/            # Member management (CRUD)
├── assets/
│   ├── css/            # Custom stylesheet
│   ├── img/            # Static images (favicon, profile)
│   └── js/             # Custom scripts
├── auth/               # Login, profile, password management
├── config/
│   └── database.php    # PDO connection + helper functions
├── database/
│   └── cashflow.sql    # Full schema + seed data
├── includes/
│   ├── header.php      # Shared sidebar & topbar layout
│   └── footer.php      # Scripts & closing tags
├── kas/                # Cash income management
├── laporan/            # PDF export module
├── pengeluaran/        # Expense management
├── vendor/             # Composer dependencies
├── index.php           # Main dashboard
├── login.php           # Auth entry point
├── migrate.php         # DB migration utility script
└── composer.json
```

---

## 🚀 Installation (Local)

### Prerequisites
- PHP 8.0+
- MySQL 8.0+
- Composer
- Web server (XAMPP / WAMP / Laragon)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/B3rlinSugi/cash-flow.git
cd cash-flow

# 2. Install PHP dependencies
composer install

# 3. Create a local MySQL database
mysql -u root -p -e "CREATE DATABASE cashflow_db;"

# 4. Import schema and seed data
mysql -u root -p cashflow_db < database/cashflow.sql
```

Then update `config/database.php` with your local credentials:

```php
$host = 'localhost';
$port = 3306;
$user = 'root';
$pass = '';
$db   = 'cashflow_db';
```

Access at: `http://localhost/cash-flow`

---

## ☁️ Environment Variables (Railway)

When deploying to [Railway](https://railway.app), the app reads database config from environment variables automatically. Add the following to your Railway service:

| Variable | Description |
|---|---|
| `MYSQLHOST` | Railway MySQL hostname |
| `MYSQLPORT` | MySQL port (default: 3306) |
| `MYSQLUSER` | MySQL username |
| `MYSQLPASSWORD` | MySQL password |
| `MYSQLDATABASE` | Database name (e.g. `railway`) |

> After first deploy, open the Railway **Database → Data → Query** tab and run the contents of `database/cashflow.sql` to initialize tables and seed data.

---

## 🔑 Default Credentials

| Field | Value |
|---|---|
| **Username** | `admin` |
| **Password** | `password` |

> ⚠️ Change the default password after your first login in the **Profil** menu.

---

## 👨‍💻 Author

**Berlin Sugiyanto Hutajulu**

[![GitHub](https://img.shields.io/badge/GitHub-B3rlinSugi-181717?style=flat&logo=github)](https://github.com/B3rlinSugi)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-berlinsugi-0A66C2?style=flat&logo=linkedin)](https://linkedin.com/in/berlinsugi)
[![Portfolio](https://img.shields.io/badge/Portfolio-berlinsugi.vercel.app-4e73df?style=flat&logo=vercel)](https://berlinsugi.vercel.app)

---

<p align="center">Built with ❤️ for class financial management · Deployed on Railway</p>
