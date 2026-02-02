# 🧺 LaundryKu - Sistem Informasi Laundry

Aplikasi web manajemen laundry berbasis Laravel yang memudahkan operasional bisnis laundry mulai dari manajemen pesanan, pelacakan status, hingga notifikasi otomatis ke pelanggan.

## ✨ Fitur Utama
- **Manajemen Pesanan:** Buat, update, dan lacak status laundry (Pending, Process, Finished, Delivered).
- **Auto-Detect Lokasi:** Hitung jarak otomatis menggunakan GPS untuk biaya antar-jemput.
- **Notifikasi Real-time:** Customer & Admin mendapat notifikasi lonceng saat ada update status atau order baru.
- **Role-Based Access:** Panel khusus untuk Admin dan Customer.
- **Laporan & Statistik:** Dashboard informatif untuk memantau performa bisnis.

## 🛠️ Prasyarat (Requirements)
Pastikan software berikut sudah terinstall di komputer Anda:
- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM
- [MySQL](https://www.mysql.com/) atau MariaDB

## 🚀 Panduan Instalasi (Clone Project)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer Anda:

### 1. Clone Repository
Buka terminal dan jalankan perintah berikut:
```bash
git clone https://github.com/username/laundry-app.git
cd laundry-app
```

### 2. Install Dependencies
Install library PHP dan JavaScript yang dibutuhkan:
```bash
composer install
npm install
```

### 3. Setup Environment (.env)
Salin file konfigurasi contoh dan sesuaikan dengan database Anda:
```bash
cp .env.example .env
```
Buka file `.env` dan atur konfigurasi database:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```
*Pastikan Anda sudah membuat database kosong dengan nama yang sesuai di MySQL.*

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Setup Database (Migrate & Seed)
Jalankan migrasi untuk membuat tabel dan data dummy awal (termasuk akun Admin default):
```bash
php artisan migrate --seed
```

### 6. Build Frontend Assets
Compile file CSS dan JS (Tailwind & Alpine.js):
```bash
npm run build
```

### 7. Jalankan Aplikasi
Jalankan server lokal Laravel:
```bash
php artisan serve
```
Akses aplikasi di: `http://localhost:8000`

---

## 👤 Akun Default (Login)
Gunakan akun berikut untuk masuk:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@laundry.com` | `password` |
| **Customer** | `customer@laundry.com` | `password` |

## 📦 Update & Maintenance
Jika Anda menarik update terbaru dari repository (`git pull`), jangan lupa jalankan:
```bash
composer install
npm install
npm run build
php artisan migrate
```

## 📝 Catatan Penting
- **Notifikasi Email:** Untuk testing di lokal, ubah `MAIL_MAILER=log` di `.env` agar email masuk ke `storage/logs/laravel.log` alih-alih dikirim sungguhan.
- **GPS:** Fitur lokasi membutuhkan browser permission untuk mengakses Geolocation.

---
Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS.
