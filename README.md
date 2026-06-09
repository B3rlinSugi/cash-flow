<div align="center">
  <br />
  <h1>💸 Cash Flow Management System</h1>
  <p>
    <strong>A Secure Financial Tracking App Upgraded from Legacy Code</strong>
  </p>
  <p>
    <img src="https://img.shields.io/badge/PHP_8-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8" />
    <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL" />
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
    <img src="https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js" />
  </p>
  <p>
    <a href="https://cash-flow-pink.vercel.app/" target="_blank">View Live Demo</a>
  </p>
</div>

---

## 📌 Overview

**Cash Flow** is a robust financial tracking application designed to monitor pending payments, cash-ins, and expenditures. Originally a legacy application, this project represents a massive architectural overhaul focusing on **Database Modernization**, **Schema Redesign**, and **Security Hardening**.

The system now supports dual-database connections (PostgreSQL and MySQL) via PDO abstractions, ensuring high availability and vendor flexibility for enterprise deployments.

## ✨ Legacy Code Upgrade & Security Migration

The core achievement of this project is the migration from insecure legacy standards to modern security protocols:
- **MD5 to Bcrypt Migration**: Completely eradicated vulnerable MD5 password hashing. Replaced with robust `bcrypt` hashing, incorporating automatic salt generation.
- **InnoDB Schema Redesign**: Migrated from outdated MyISAM to strictly enforced InnoDB (MySQL) and PostgreSQL schemas, introducing ACID compliance and foreign key constraints.
- **SQL Injection Prevention**: All raw queries were refactored into strict PDO Prepared Statements.

## 📊 Core Application Features

- **Interactive Financial Dashboard**: Utilizes `Chart.js` to render dynamic 6-month cash flow trends, giving instant visibility into organizational financial health.
- **Pending Payment Tracking**: Specialized modules to track accounts receivable (anggota) and operational expenditures (pengeluaran).
- **Dual Database Support**: Seamlessly switch between PostgreSQL and MySQL simply by changing environment parameters.
- **Advanced PDF Export Filtering**: Integrated `TCPDF/FPDF` to allow administrators to generate deeply filtered financial reports (e.g., date ranges, specific categories) directly to PDF format.

---

## 🛠️ Tech Stack & Architecture

- **Backend**: Native PHP 8
- **Database Adapters**: PDO (PHP Data Objects)
- **Supported Databases**: PostgreSQL 14+, MySQL 8+
- **Frontend UI**: Bootstrap 5, Custom CSS
- **Data Visualization**: Chart.js
- **Document Generation**: TCPDF / FPDF

---

## 🚀 Getting Started

### Prerequisites
- **Apache Web Server**
- **PHP 8.0+**
- **PostgreSQL** or **MySQL** Database

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/B3rlinSugi/cash-flow.git
   cd cash-flow
   ```

2. **Database Setup:**
   Choose your preferred database engine. SQL dumps are provided in the `/database` folder:
   - For MySQL: Import `database/mysql_schema.sql`
   - For PostgreSQL: Execute the schema inside `database/postgres_schema.sql`

3. **Configure Connection:**
   - Open `config/database.php`.
   - Update the PDO connection to point to either `mysql:` or `pgsql:` along with your credentials.

4. **Run the Application:**
   - Host the directory inside your local web server environment (`htdocs` or `www`).
   - Access via browser: `http://localhost/cash-flow`.

---

## 👨‍💻 Author

**Berlin Sugiyanto**  
Backend Developer & System Architect  
- Portfolio: [berlinsugi.vercel.app](https://berlinsugi.vercel.app/)
- LinkedIn: [linkedin.com/in/berlinsugi](https://linkedin.com/in/berlinsugi)

---

<div align="center">
  <i>"Modernizing legacy code is just as important as writing new code."</i>
</div>
