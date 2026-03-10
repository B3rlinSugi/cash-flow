# 💰 Cash Flow Class — Financial Management System

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

Organization cash flow management system built with PHP and MySQL. Upgraded from legacy MD5 authentication to bcrypt, restructured with PDO and MVC separation, and enhanced with analytics dashboard.

---

## Features

- Secure login with bcrypt authentication and session management
- Cash income recording with member management and tiered levels (Reguler, Silver, Gold, Platinum)
- Expense tracking with categorization
- Deferred payment module with automatic status management (pending to settled)
- Real-time dashboard with 6-month cash flow analytics using Chart.js
- PDF report export filtered by month and transaction type
- Admin profile and password management

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8, PDO |
| Database | MySQL 8 (InnoDB, Foreign Keys, Indexing) |
| Frontend | Bootstrap 5, Vanilla JS, Chart.js |
| Auth | bcrypt password hashing, Session Management |
| Security | PDO Prepared Statements |

---

## Upgrade dari Versi Sebelumnya

| Aspek | Sebelumnya | Versi ini |
|---|---|---|
| Password | MD5 (tidak aman) | bcrypt |
| Database query | MySQLi | PDO Prepared Statements |
| Struktur folder | Semua di root | MVC terstruktur |
| Database engine | MyISAM | InnoDB dengan Foreign Key |
| Dashboard | Tidak ada | Chart.js analytics |
| Export | Tidak ada | PDF report |

---

## Installation

1. Clone this repository
2. Import `database/cashflow.sql` to MySQL
3. Configure database connection in `config/database.php`
4. Run on localhost using XAMPP or Laragon
5. Login: `admin` / `admin123`

---

## Project Structure

```
cash-flow/
├── config/         — database connection and helpers
├── includes/       — shared header and footer
├── kas/            — cash income management
├── pengeluaran/    — expense management
├── anggota/        — member management
├── laporan/        — report and PDF export
├── auth/           — login, logout, profile
├── assets/         — CSS, JS
└── database/       — SQL schema
```

---

## Author

**Berlin Sugiyanto** — Junior Backend Developer
- GitHub: [github.com/B3rlinSugi](https://github.com/B3rlinSugi)
- LinkedIn: [linkedin.com/in/berlinsugi](https://linkedin.com/in/berlinsugi)
- Email: berlinsugiyanto23@gmail.com
