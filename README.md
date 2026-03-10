# 💰 Cash Flow Class — Sistem Manajemen Keuangan

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?style=flat-square&logo=chartdotjs&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

Aplikasi web manajemen keuangan kas kelas/organisasi berbasis PHP & MySQL. Dilengkapi dashboard dengan grafik arus kas, manajemen anggota berlevel, export laporan PDF, dan autentikasi bcrypt yang aman.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔐 **Autentikasi Aman** | Login dengan bcrypt password hashing (menggantikan MD5) + session regeneration |
| 📊 **Dashboard & Grafik** | Visualisasi arus kas 6 bulan terakhir menggunakan Chart.js |
| 👥 **Manajemen Anggota** | CRUD anggota dengan level: Reguler, Silver, Gold, Platinum |
| 💵 **Kas Masuk** | Pencatatan setoran kas per anggota + fitur tunda pembayaran |
| 📉 **Pengeluaran** | Pencatatan dan manajemen pengeluaran organisasi |
| 📄 **Export Laporan PDF** | Generate laporan bulanan kas masuk & pengeluaran via browser print |
| 🔍 **Pencarian & Pagination** | Pencarian data real-time dengan pagination |
| 🗄️ **Database Terstruktur** | Foreign key, index, dan relasi tabel yang proper |

---

## 🛠️ Tech Stack

- **Backend** — PHP 8.x, PDO (prepared statements)
- **Database** — MySQL 8.0 dengan Foreign Key & Index
- **Frontend** — Bootstrap 5.3, Font Awesome 6, Chart.js 4
- **Security** — `password_hash()` bcrypt, session regeneration, XSS protection

---

## 📁 Struktur Proyek

```
cash-flow/
├── config/
│   └── database.php        # PDO connection, auth & helper functions
├── includes/
│   ├── header.php          # Shared sidebar + topbar layout
│   └── footer.php          # Shared footer + scripts
├── auth/
│   ├── logout.php
│   ├── profil.php
│   └── ganti-password.php
├── kas/
│   ├── index.php           # Daftar kas masuk
│   ├── setor.php           # Form setor kas
│   ├── edit.php
│   ├── hapus.php
│   └── ditunda.php         # Kas yang ditunda
├── anggota/
│   ├── index.php           # Daftar anggota
│   ├── tambah.php
│   ├── edit.php
│   └── detail.php
├── pengeluaran/
│   ├── index.php           # Daftar pengeluaran
│   ├── tambah.php
│   └── edit.php
├── laporan/
│   └── index.php           # Export laporan PDF
├── assets/
│   ├── css/style.css
│   └── js/main.js
├── database/
│   └── cashflow.sql        # Skema + data dummy
├── login.php
└── index.php               # Dashboard + grafik
```

---

## 🗄️ Skema Database

```sql
admin          -- Tabel admin dengan bcrypt password
anggota        -- Anggota dengan level (Reguler/Silver/Gold/Platinum)
kas            -- Pemasukan kas (FK → anggota)
pengeluaran    -- Pengeluaran organisasi
kas_ditunda    -- Kas yang belum dibayar (FK → anggota)
```

> Semua tabel menggunakan **InnoDB** dengan **Foreign Key Constraint** dan **Index** untuk performa query optimal.

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP 8.0+
- MySQL 8.0+
- Web server (Apache/Nginx) atau XAMPP/Laragon

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/B3rlinSugi/cash-flow.git
cd cash-flow

# 2. Import database
mysql -u root -p < database/cashflow.sql
```

```php
// 3. Sesuaikan konfigurasi di config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cashflow_db');
define('DB_USER', 'root');
define('DB_PASS', '');   // sesuaikan password MySQL kamu
```

```bash
# 4. Jalankan dengan PHP built-in server (development)
php -S localhost:8000
# atau arahkan web server ke folder ini
```

---

## 🔑 Akun Default

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `password123` |

> ⚠️ Ganti password setelah pertama kali login melalui menu **Pengaturan → Ganti Password**

---

## 📸 Screenshots

> *(Tambahkan screenshot dashboard, form setor kas, dan halaman laporan di sini)*

---

## 🔄 Perubahan dari Versi Sebelumnya

| Sebelum (v1) | Sesudah (v2) |
|---|---|
| MD5 password hashing ❌ | **bcrypt (`password_hash()`)** ✅ |
| MySQLi procedural | **PDO dengan prepared statements** ✅ |
| Semua file di root folder | **Struktur folder terorganisir** ✅ |
| Tanpa foreign key | **Foreign Key + Index** ✅ |
| Tanpa grafik | **Chart arus kas 6 bulan (Chart.js)** ✅ |
| Export Excel sederhana | **Export Laporan PDF** ✅ |
| Bootstrap 4 (vendor lokal) | **Bootstrap 5.3 via CDN** ✅ |

---

## 👨‍💻 Developer

**Berlin Sugi** — Fresh Graduate Teknik Informatika, Universitas Gunadarma (GPA 3.63)

[![GitHub](https://img.shields.io/badge/GitHub-B3rlinSugi-181717?style=flat-square&logo=github)](https://github.com/B3rlinSugi)

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).
