# 💰 Cash Flow Class — Financial Management System

> A legacy financial management system modernized with production-grade security, analytics dashboard, and exportable reporting — built with PHP 8 and MySQL.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-336791?style=flat-square&logo=postgresql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![Status](https://img.shields.io/badge/Status-Complete-brightgreen?style=flat-square)

---

## 📌 Overview

Cash Flow Class is a financial management system built for organizational cash flow tracking. This project focused on **upgrading a legacy codebase** to modern security and performance standards — a real-world scenario common in software maintenance roles.

Key highlights:
- **Migrated MD5 → bcrypt** with zero breaking changes to existing user data
- **Redesigned database schema** with InnoDB engine, FK constraints, and proper indexing
- **6-month analytics dashboard** with deferred payment tracking via Chart.js
- **PDF report export** filterable by month and transaction type for auditable records

---

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────┐
│                  CLIENT LAYER               │
│         Browser (HTML/CSS/Bootstrap 5)      │
└────────────────────┬────────────────────────┘
                     │ HTTP Request
┌────────────────────▼────────────────────────┐
│               APPLICATION LAYER             │
│                  PHP 8 (MVC)                │
│  ┌──────────┐ ┌──────────┐ ┌─────────────┐ │
│  │   Auth   │ │   Cash   │ │  Analytics  │ │
│  │ (bcrypt) │ │   Flow   │ │  Dashboard  │ │
│  └──────────┘ └──────────┘ └─────────────┘ │
│  ┌──────────┐ ┌──────────┐                  │
│  │ Deferred │ │   PDF    │                  │
│  │ Payment  │ │  Export  │                  │
│  └──────────┘ └──────────┘                  │
└────────────────────┬────────────────────────┘
                     │ PDO (Prepared Statements)
┌────────────────────▼────────────────────────┐
│                DATABASE LAYER               │
│           MySQL / PostgreSQL (InnoDB)       │
│   users │ transactions │ categories         │
│   deferred_payments │ export_logs │ ...     │
└─────────────────────────────────────────────┘
```

---

## ✨ Features

### 🔐 Security Upgrade
- Migrated legacy MD5 hashing to bcrypt (cost factor 12) with zero data loss
- Redesigned authentication flow with session security hardening
- PDO prepared statements across all queries — SQL injection prevention

### 💸 Cash Flow Management
- Income and expense transaction recording with category tagging
- Deferred payment module with automatic status tracking (pending → settled)
- Transaction history with search and filter by date, category, type

### 📊 Analytics Dashboard
- 6-month cash flow visualization using Chart.js (income vs expense)
- Monthly summary cards with balance calculation
- Deferred payment aging report

### 📄 PDF Export
- Exportable financial report filterable by month and transaction type
- Clean, print-ready format suitable for organizational audits

---

## 🗄️ Database Schema

| Table | Description |
|---|---|
| `users` | User accounts with bcrypt hashed passwords |
| `transactions` | Income/expense records with category and date |
| `categories` | Transaction category master |
| `deferred_payments` | Deferred payment records with due date and status |
| `export_logs` | Tracks report export history per user |

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.x |
| Database | MySQL 8 / PostgreSQL 15 (InnoDB) |
| DB Access | PDO with Prepared Statements |
| Frontend | Bootstrap 5, HTML5, CSS3, JavaScript |
| Charts | Chart.js |
| PDF Export | TCPDF / FPDF |
| Security | bcrypt, Session Hardening |
| Version Control | Git & GitHub |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.x
- MySQL 8.0+ or PostgreSQL 15+
- XAMPP / Laragon / any local server

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/B3rlinSugi/cash-flow.git
cd cash-flow

# 2. Import the database
mysql -u root -p < database/cashflow.sql

# 3. Configure database connection
cp config/config.example.php config/config.php
# Edit config.php with your DB credentials

# 4. Run the application
# Place folder in htdocs (XAMPP) or www (Laragon)
# Access via: http://localhost/cash-flow
```

### Default Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@cashflow.com | admin123 |

---

## 📁 Project Structure

```
cash-flow/
├── config/
│   └── config.php           # DB connection & app config
├── database/
│   └── cashflow.sql         # Full DB schema + seed data
├── src/
│   ├── controllers/         # Business logic handlers
│   ├── models/              # DB query abstraction
│   └── views/               # HTML templates
├── public/
│   ├── assets/              # CSS, JS, images
│   └── index.php            # Entry point
└── README.md
```

---

## 🔑 Key Technical Decisions

**Why migrate MD5 → bcrypt?**
MD5 is a fast hashing algorithm not designed for passwords — it's vulnerable to brute-force and rainbow table attacks. bcrypt is computationally expensive by design and includes automatic salting, making it the industry standard for password storage.

**Why PDO over raw MySQLi?**
PDO supports prepared statements natively and is database-agnostic, allowing the system to support both MySQL and PostgreSQL without rewriting query logic.

**Why InnoDB over MyISAM?**
InnoDB supports foreign key constraints and ACID transactions, which are essential for maintaining financial data integrity — especially in deferred payment tracking where status changes must be atomic.

---

## 🧪 Testing Results

| Scenario | Result |
|---|---|
| MD5 → bcrypt migration (existing users) | ✅ 0 breaking changes |
| Deferred payment status auto-update | ✅ Accurate |
| PDF export (all filter combinations) | ✅ Consistent output |
| SQL injection attempts via input fields | ✅ All blocked |

---

## 📄 License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

## 👤 Author

**Berlin Sugiyanto**
- 🌐 Portfolio: [berlinsugi.vercel.app](https://berlinsugi.vercel.app)
- 💼 LinkedIn: [linkedin.com/in/berlinsugi](https://linkedin.com/in/berlinsugi)
- 📧 Email: berlinsugiyanto23@gmail.com
