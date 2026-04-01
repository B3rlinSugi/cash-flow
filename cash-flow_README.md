# 💰 Cash Flow - Financial Management System

A comprehensive financial management system with cash flow analytics, visual dashboards, and PDF report generation.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)
![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?style=flat)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap)

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Schema](#database-schema)
- [Installation](#installation)
- [Usage](#usage)
- [Dashboard Features](#dashboard-features)
- [License](#license)

---

## ✨ Features

- 📊 **Cash Flow Dashboard** - Visual 6-month analytics
- 📈 **Financial Reports** - Income & expense tracking
- 📉 **Trend Analysis** - Visual charts showing financial trends
- 🖨️ **PDF Export** - Generate downloadable financial reports
- 🔒 **Secure Authentication** - bcrypt password hashing
- 👥 **User Management** - Multi-user support
- 🔍 **Search & Filter** - Find transactions easily

---

## 🛠 Tech Stack

| Technology | Description |
|------------|-------------|
| **PHP 8.0+** | Server-side scripting |
| **MySQL** | Relational Database |
| **Bootstrap 5** | Frontend framework |
| **Chart.js** | Data visualization |
| **Dompdf** | PDF generation |

---

## 🗄 Database Schema

### Core Tables
- `users` - User accounts
- `transactions` - Income and expense records
- `categories` - Transaction categories
- `accounts` - Bank accounts / cash accounts

### Transaction Types
- **Income** - Revenue, investments, other income
- **Expense** - Operational costs, purchases, utilities

---

## 🚀 Installation

### Prerequisites
- PHP 8.0+
- MySQL 5.7+
- Web Server (XAMPP/WAMP/LAMP)

### Steps

1. **Clone the repository**
```bash
git clone https://github.com/B3rlinSugi/cash-flow.git
cd cash-flow
```

2. **Configure database**
```sql
CREATE DATABASE cashflow;
```

3. **Import database schema**
```bash
mysql -u root -p cashflow < database.sql
```

4. **Configure application**
```php
// Update database credentials in config
```

5. **Run the application**
```bash
# Place in htdocs folder (XAMPP)
# Access: http://localhost/cash-flow
```

---

## 📊 Dashboard Features

### Overview Dashboard
- Total income (current month)
- Total expenses (current month)
- Net profit/loss
- Cash flow summary

### 6-Month Analytics
- Monthly income vs expenses chart
- Trend line visualization
- Category-wise breakdown
- Comparative analysis

### Reports
- Monthly financial reports
- Category-wise analysis
- Export to PDF format

---

## 📁 Project Structure

```
cash-flow/
├── config/
├── views/
│   ├── dashboard.php
│   ├── transactions.php
│   ├── reports.php
│   └── auth/
├── assets/
│   ├── css/
│   └── js/
├── vendor/
├── index.php
└── README.md
```

---

## 👨‍💻 Author

**Berlin Sugiyanto**
- Email: berlinsugiyanto23@gmail.com
- GitHub: [@B3rlinSugi](https://github.com/B3rlinSugi)
- LinkedIn: [berlinsugi](https://linkedin.com/in/berlinsugi)

---

<p align="center">
  Built with ❤️ for financial management
</p>
