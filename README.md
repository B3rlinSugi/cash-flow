# 💰 Cash Flow Manager — Financial Visualization System

A comprehensive financial modernization system built with **PHP** and **MySQL**. Re-engineered from a legacy codebase, this platform features industrial-grade Bcrypt security, ACID-compliant relational integrity, and high-fidelity data visualization.

[![Live Demo](https://img.shields.io/badge/Live%20Demo-cashflow.up.railway.app-4e73df?style=for-the-badge&logo=railway&logoColor=white)](https://cash-flow-production-d733.up.railway.app)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://chartjs.org)

---

## 🏗 System Architecture

The transition from legacy logic to a modern structured flow involves a secure Auth middleware and normalized data handling.

```mermaid
graph TD
    User["👤 Student / Admin"]
    Auth["🔒 Bcrypt-Secured Auth"]
    Finance["🏦 Financial Logic (Income/Expense/Deferred)"]
    Report["📄 Report Generator (Dompdf)"]
    Chart["📈 Analytics Engine (Chart.js)"]
    DB[("🗄️ MySQL (FK Constrained)")]

    User --> Auth
    Auth --> Finance
    Finance --> DB
    Finance --> Report
    Finance --> Chart
```

---

## ✨ Features

- **🛡 Modernized Security:** Successfully migrated from legacy MD5 hashing to secure Bcrypt patterns with cost factors of 12.
- **📊 Fiscal Analytics:** Real-time 6-month income vs expense visualization using interactive Chart.js modules.
- **⏳ Deferred Tracking:** Specialized logical flow for tracking and alerting on pending/deferred member payments.
- **🖨 Audit Reports:** Server-side PDF generation for formal financial records with period-based filtering.
- **🔗 Relational Integrity:** Strictly enforced DB constraints (InnoDB) to ensure 0% orphaned financial records.

---

## 🗄 Database Schema

The schema is optimized for financial consistency and historical auditing.

```mermaid
erDiagram
    ANGGOTA ||--o{ KAS : "contributes"
    ANGGOTA ||--o{ KAS_DITUNDA : "owes"
    ANGGOTA {
        int id PK
        string nama
        string level_kas
    }
    KAS {
        int id PK
        int anggota_id FK
        decimal jumlah
    }
```

---

## 🚀 Local Installation

### Prerequisites
- PHP 8.1+
- MySQL 8.0
- Composer

### Setup Steps
1. **Clone & Setup:**
   ```bash
   git clone https://github.com/B3rlinSugi/cash-flow.git
   cd cash-flow
   composer install
   ```

2. **Database:**
   Update `config/database.php` with your credentials and initialize the schema using `database/cashflow.sql`.

---

## 👨‍💻 Developed By

**Berlin Sugiyanto Hutajulu**

[![GitHub](https://img.shields.io/badge/GitHub-B3rlinSugi-181717?style=flat&logo=github)](https://github.com/B3rlinSugi)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-berlinsugi-0A66C2?style=flat&logo=linkedin)](https://linkedin.com/in/berlinsugi)
[![Portfolio](https://img.shields.io/badge/Portfolio-berlinsugi.vercel.app-4e73df?style=flat&logo=vercel)](https://berlinsugi.vercel.app)

---
<p align="center">Built with ❤️ and Modern PHP · Financial Integrity Simplified</p>
gi)
[![Portfolio](https://img.shields.io/badge/Portfolio-berlinsugi.vercel.app-4e73df?style=flat&logo=vercel)](https://berlinsugi.vercel.app)

---

<p align="center">Built with ❤️ for class financial management · Deployed on Railway</p>
