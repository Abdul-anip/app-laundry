# 🧺 VIP Laundry - Premium Laundry Management System

Sistem Informasi Manajemen Laundry modern berbasis web yang dirancang untuk efisiensi operasional bisnis laundry. Dibangun menggunakan **Laravel 10** dan **Filament PHP**, aplikasi ini menawarkan pengalaman manajemen yang cepat, responsif, dan mudah digunakan.

![Dashboard Preview](https://filamentphp.com/images/home/screenshot-light.jpg) 
*(Ganti dengan screenshot dashboard aplikasi Anda)*

## ✨ Fitur Unggulan

### 🏢 Untuk Admin (Owner/Kasir)
- **Dashboard Interaktif:** Pantau pendapatan bulanan, status order, dan grafik kinerja bisnis secara realtime.
- **POS System (Point of Sale):**
  - Input order cepat untuk layanan Kiloan & Satuan (Bundle).
  - **Auto-Save Customer:** Input pelanggan manual otomatis tersimpan sebagai member baru.
  - **Smart Location:** Deteksi lokasi & alamat otomatis untuk delivery.
  - Cetak struk/nota instan.
- **Manajemen Order:** Lacak status cucian (Queue, Process, Ready, Delivered) dengan mudah.
- **Manajemen Pelanggan:** Database member terpusat dengan riwayat transaksi.
- **Laporan Keuangan:** Rekap pendapatan harian/bulanan bisa diexport.

### 📱 Untuk Pelanggan (Customer)
- **Tracking Order:** Cek status cucian realtime via kode resi/order.
- **Request Jemput:** Pesan layanan antar-jemput dari rumah.
- **Loyalty Points:** Kumpulkan poin setiap transaksi.

---

## 🛠️ Teknologi yang Digunakan
- [Laravel 10](https://laravel.com) - Framework PHP Modern
- [Filament PHP 3](https://filamentphp.com) - Admin Panel & Form Builder
- [Livewire 3](https://livewire.laravel.com) - Full-stack Dynamic Interface
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS Framework
- [MySQL](https://mysql.com) - Database Management

---

## 🚀 Panduan Instalasi (Untuk Developer)

### 1. Prasyarat Sistem
Pastikan komputer Anda sudah terinstall:
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB

### 2. Clone & Setup Project
```bash
# Clone repository
git clone https://github.com/username/laundry-app.git
cd laundry-app

# Install PHP dependencies
composer install

# Install JS dependencies
npm install
```

### 3. Konfigurasi Environment
```bash
# Salin file env
cp .env.example .env

# Generate Application Key
php artisan key:generate
```
*Buka file `.env` dan sesuaikan koneksi database Anda (DB_DATABASE, DB_USERNAME, dll).*

### 4. Database & Admin
```bash
# Jalankan migrasi dan data dummy
php artisan migrate --seed

# (Opsional) Buat user Filament baru jika perlu
php artisan make:filament-user
```

### 5. Jalankan Aplikasi
```bash
# Compile aset frontend
npm run build

# Jalankan server
php artisan serve
```
Akses Admin Panel di: `http://localhost:8000/admin`

---

## 👤 Akun Demo Default

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Super Admin** | `admin@laundry.com` | `password` | `/admin` |
| **Customer** | `customer@laundry.com` | `password` | `/` (Halaman Depan) |

---

## � Panduan Penggunaan Singkat

### Menggunakan POS System
1. Login ke panel Admin (`/admin`).
2. Pilih menu **POS System** di sidebar.
3. Pilih Jenis Pelanggan:
   - **Registered:** Cari nama member yang sudah ada.
   - **Walk-in:** Ketik nama & nomor HP (otomatis dibuatkan akun member).
4. Pilih Layanan (Kiloan/Satuan) & Masukkan Berat/Jumlah.
5. Klik **"Get Current Location"** untuk isi alamat otomatis (jika delivery).
6. Proses pembayaran & Cetak Struk.

---

## 📝 Catatan Tambahan
- **Geolocation:** Fitur "Get Location" membutuhkan izin akses lokasi pada browser dan koneksi internet (untuk OpenStreetMap).
- **Mode Produksi:** Untuk deploy ke hosting, pastikan menjalankan `php artisan filament:optimize` agar performa lebih cepat.

Dibuat dengan ❤️ untuk kemudahan bisnis laundry Anda.
