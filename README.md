<div align="center">

<img src="https://capsule-render.vercel.app/api?type=waving&color=0:1a1a2e,50:16213e,100:0f3460&height=180&section=header&text=Cash%20Flow%20Class&fontSize=45&fontColor=e94560&animation=fadeIn&fontAlignY=38&desc=Financial%20Management%20System%20%7C%20PHP%208%20%7C%20MySQL%20%7C%20bcrypt&descAlignY=55&descColor=a8b2d8" />

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Live Demo](https://img.shields.io/badge/Live_Demo-Railway-0B0D0E?style=for-the-badge&logo=railway&logoColor=white)](https://cash-flow-production-d733.up.railway.app/login.php)
[![Status](https://img.shields.io/badge/Status-Complete-brightgreen?style=for-the-badge)](https://github.com/B3rlinSugi/cash-flow)
[![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)

</div>

---

## 🌐 Live Demo

> **[https://cash-flow-production-d733.up.railway.app/login.php](https://cash-flow-production-d733.up.railway.app/login.php)**

Aplikasi sudah di-deploy di Railway dan dapat diakses secara publik.

---

## 📌 Overview

**Cash Flow Class** adalah sistem manajemen keuangan kas organisasi yang dibangun dengan fokus pada **modernisasi legacy codebase** ke standar produksi — skenario nyata yang umum ditemui dalam peran software maintenance dan backend development.

> 💡 **Nilai utama proyek ini:** Kemampuan untuk **mengambil sistem lama yang sudah berjalan**, mengidentifikasi celah keamanan dan arsitektur, lalu melakukan upgrade secara bertahap tanpa merusak fungsi yang sudah ada.

### 🏆 Highlight Upgrade

| Yang Diubah | Sebelum | Sesudah |
|---|---|---|
| Password hashing | ❌ MD5 (tidak aman) | ✅ bcrypt (cost factor 12) |
| Database engine | ❌ MyISAM | ✅ InnoDB + FK constraints |
| Query method | ❌ Raw MySQL | ✅ PDO Prepared Statements |
| Analytics | ❌ Tidak ada | ✅ Dashboard 6 bulan |
| Pelaporan | ❌ Manual | ✅ PDF export terfilter |

---

## ✨ Fitur Utama

### 🔐 Security Upgrade
- **Migrasi MD5 → bcrypt** tanpa breaking change pada data user existing
- Redesain alur autentikasi dengan session security hardening
- PDO prepared statements di semua query — proteksi SQL injection

### 💸 Manajemen Kas
- Pencatatan transaksi pemasukan & pengeluaran dengan kategori
- **Modul pembayaran ditangguhkan (deferred payment)** dengan tracking status otomatis: `Pending → Lunas`
- Riwayat transaksi dengan search dan filter berdasarkan tanggal, kategori, tipe

### 📊 Analytics Dashboard
- Visualisasi arus kas **6 bulan** menggunakan Chart.js (pemasukan vs pengeluaran)
- Kartu ringkasan bulanan dengan kalkulasi saldo
- Laporan aging piutang/hutang tertangguh

### 📄 PDF Export
- Laporan keuangan yang dapat diekspor, **terfilter per bulan dan tipe transaksi**
- Format print-ready yang sesuai untuk audit organisasi

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────┐
│               CLIENT LAYER                  │
│       Browser (HTML/CSS/Bootstrap 5)        │
└────────────────────┬────────────────────────┘
                     │ HTTP Request
┌────────────────────▼────────────────────────┐
│            APPLICATION LAYER (PHP 8)        │
│                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  │
│  │   Auth   │  │   Cash   │  │Analytics │  │
│  │ (bcrypt) │  │   Flow   │  │Dashboard │  │
│  └──────────┘  └──────────┘  └──────────┘  │
│  ┌──────────┐  ┌──────────┐                 │
│  │ Deferred │  │   PDF    │                 │
│  │ Payment  │  │  Export  │                 │
│  └──────────┘  └──────────┘                 │
└────────────────────┬────────────────────────┘
                     │ PDO (Prepared Statements)
┌────────────────────▼────────────────────────┐
│             DATABASE LAYER                  │
│           MySQL 8 (InnoDB, FK)              │
│  users │ transactions │ categories          │
│  deferred_payments │ export_logs            │
└─────────────────────────────────────────────┘
```

---

## 🗄️ Desain Database

| Tabel | Deskripsi |
|---|---|
| `users` | Akun pengguna dengan password bcrypt |
| `transactions` | Rekaman pemasukan/pengeluaran dengan kategori dan tanggal |
| `categories` | Master kategori transaksi |
| `deferred_payments` | Rekaman pembayaran ditangguhkan dengan due date dan status |
| `export_logs` | Riwayat export laporan per pengguna |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Language | PHP 8.x |
| Database | MySQL 8 (InnoDB, FK Constraints) |
| DB Access | PDO + Prepared Statements |
| Frontend | Bootstrap 5, HTML5, CSS3, JavaScript |
| Charts | Chart.js |
| PDF Export | TCPDF / FPDF |
| Security | bcrypt, Session Hardening |

---

## 🚀 Cara Menjalankan

### Prasyarat
- PHP 8.x
- MySQL 8.0+
- XAMPP / Laragon / web server lokal

### Instalasi

```bash
# 1. Clone repository
git clone https://github.com/B3rlinSugi/cash-flow.git
cd cash-flow

# 2. Import database
mysql -u root -p < database/cashflow.sql

# 3. Konfigurasi koneksi database
cp config/config.example.php config/config.php
# Edit config.php dengan kredensial DB kamu

# 4. Jalankan aplikasi
# Letakkan folder di htdocs (XAMPP) atau www (Laragon)
# Akses via: http://localhost/cash-flow
```

### Kredensial Default

| Role | Email | Password |
|---|---|---|
| Admin | admin@cashflow.com | admin123 |

---

## 📁 Struktur Proyek

```
cash-flow/
├── anggota/            # Manajemen anggota kas
├── assets/             # CSS, JS, images
├── auth/               # Login, logout, session handling
├── config/
│   └── config.php      # Konfigurasi DB & aplikasi
├── database/
│   └── cashflow.sql    # Skema DB lengkap + seed data
├── includes/           # Shared components
├── kas/                # Modul transaksi pemasukan/pengeluaran
├── laporan/            # Modul PDF export laporan
├── pengeluaran/        # Modul pengeluaran
├── index.php           # Dashboard utama (analytics)
└── login.php           # Halaman login
```

---

## 🔑 Keputusan Teknis

**Mengapa migrasi MD5 → bcrypt?**
MD5 adalah algoritma hashing cepat yang tidak dirancang untuk password — rentan terhadap brute-force dan rainbow table. bcrypt mahal secara komputasi by design dan menyertakan salting otomatis, menjadikannya standar industri untuk penyimpanan password.

**Mengapa PDO bukan raw MySQLi?**
PDO mendukung prepared statements secara native dan bersifat database-agnostic, memungkinkan sistem untuk mendukung MySQL maupun PostgreSQL tanpa menulis ulang logika query.

**Mengapa InnoDB bukan MyISAM?**
InnoDB mendukung foreign key constraints dan transaksi ACID, yang sangat penting untuk menjaga integritas data keuangan — terutama dalam tracking deferred payment di mana perubahan status harus bersifat atomik.

---

## 🧪 Hasil Pengujian

| Skenario | Hasil |
|---|---|
| Migrasi MD5 → bcrypt (user existing) | ✅ 0 breaking changes |
| Auto-update status deferred payment | ✅ Akurat |
| PDF export (semua kombinasi filter) | ✅ Output konsisten |
| SQL injection via input fields | ✅ Semua diblokir |

---

## ⚙️ DevOps & Deployment

Proyek ini menerapkan alur **Continuous Deployment (CD)** modern untuk iterasi yang cepat dan reliabel:

- **Platform Deployment**: [Railway](https://railway.app)
- **Workflow Otomatis**: Trigger deployment otomatis setiap kali ada perubahan yang di-*push* ke branch `main`.
- **Relational Sync**: Menggunakan utilitas migrasi skema untuk menjaga konsistensi database antar lingkungan (development & production).
- **Monitoring & Health**: Terintegrasi langsung dengan indikator status di dashboard utama Portfolio.

---

## 👤 Author

<div align="center">

**Berlin Sugiyanto Hutajulu**

[![GitHub](https://img.shields.io/badge/GitHub-B3rlinSugi-181717?style=flat&logo=github)](https://github.com/B3rlinSugi)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-berlinsugi-0A66C2?style=flat&logo=linkedin)](https://linkedin.com/in/berlinsugi)
[![Portfolio](https://img.shields.io/badge/Portfolio-berlinsugi.vercel.app-4e73df?style=flat&logo=vercel)](https://berlinsugi.vercel.app)

---

## ⚙️ DevOps & Deployment

This project uses a modern **Continuous Deployment (CD)** pipeline for rapid iteration:

- **Deployment Platform**: [Railway](https://railway.app)
- **Workflow**: Automated triggers on `git push` to `main`.
- **Relational Sync**: Schema migration utility ensures database consistency between environments.
- **Monitoring**: Integrated health-check signals via the core Portfolio Dashboard.

---
<p align="center">Built with ❤️ and Modern PHP · Financial Integrity Simplified</p>
>>>>>>> f986667 (docs: add DevOps and Deployment section to README)
