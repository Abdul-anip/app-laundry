# 🧺 VIP Laundry — Premium Laundry Management System

Sistem Informasi Manajemen Laundry berbasis web yang modern, lengkap, dan responsif. Dibangun dengan **Laravel 12** dan dengan panel admin berbasis **Blade + Tailwind CSS** yang cepat dan intuitif.

---

## ✨ Fitur Unggulan

### 🏢 Panel Admin

| Modul | Deskripsi |
|---|---|
| **Dashboard** | Statistik pendapatan bulanan, total order, pelanggan baru, order aktif, grafik pendapatan 30 hari, donut chart status, dan tabel order terbaru. |
| **POS System** | Input order offline (Walk-in & Member), pemilihan layanan Kiloan atau Satuan/Bundle, validasi promo otomatis, auto-save pelanggan baru, deteksi lokasi (Geolocation + OpenStreetMap), cetak struk PDF. |
| **Manajemen Order** | Lacak & kelola status cucian: `pending → pickup → process → finished → delivered → completed`. Filter multi-status, pencarian by kode/nama/HP, input berat aktual & recalculate harga, advance status, lihat riwayat tracking order. |
| **WhatsApp Integration** | Notifikasi otomatis ke pelanggan via deep link WA: konfirmasi penjemputan, pengiriman tagihan/invoice, dan update status order. |
| **Cetak / Download** | Unduh struk order (PDF) dan laporan keuangan harian/bulanan (PDF). |
| **Layanan & Bundle** | Kelola jenis layanan (harga/kg) dan paket Bundle (satuan/harga tetap). |
| **Promo & Diskon** | Buat & kelola promo dengan tipe `persen` atau `nominal`, lengkap dengan kode promo untuk validasi di POS. |
| **Manajemen Pelanggan** | Lihat database member, riwayat transaksi, dan total poin loyalitas. |
| **Ulasan** | Pantau semua review & rating pelanggan. |
| **Notifikasi Admin** | Bell notifikasi real-time untuk order baru, dengan fitur tandai-baca & tandai-semua-baca. |
| **Pengaturan** | Konfigurasi informasi toko, tampilan landing page, dan parameter sistem. |

### 📱 Portal Pelanggan (Customer)

| Modul | Deskripsi |
|---|---|
| **Dashboard** | Halaman utama pelanggan setelah login. |
| **Buat Order** | Pesan layanan laundry (pickup/antar-jemput) dari rumah, pilih layanan atau bundle. |
| **Riwayat Order** | Lihat semua order beserta status terkini dan rincian biaya. |
| **Konfirmasi Terima** | Konfirmasi penerimaan cucian. Jika tidak dikonfirmasi dalam 24 jam setelah status `delivered`, sistem otomatis menandai sebagai `completed`. |
| **Ulasan & Rating** | Beri review & bintang untuk order yang sudah `completed`. |
| **Download Bukti** | Unduh bukti/struk order. |
| **Loyalty Points** | Kumpulkan poin dari setiap transaksi (1 poin per Rp 1.000). |

### 🌐 Halaman Publik

- **Landing Page** — Tampilan profesional dengan konten yang bisa dikonfigurasi lewat panel Setting.
- **Order Tracking Publik** — Lacak status cucian hanya dengan memasukkan kode order (tanpa login).

---

## 🛠️ Teknologi

| Layer | Teknologi |
|---|---|
| **Framework** | [Laravel 12](https://laravel.com) |
| **Admin UI** | Custom Blade Layout + [Tailwind CSS](https://tailwindcss.com) |
| **Auth** | [Laravel Breeze](https://laravel.com/docs/starter-kits) |
| **PDF** | [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) |
| **Chart Trend** | [flowframe/laravel-trend](https://github.com/Flowframe/laravel-trend) |
| **Database** | MySQL / SQLite |
| **Queue** | Laravel Queue (untuk notifikasi) |

---

## 🚀 Panduan Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB (atau SQLite untuk development)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/laundry-app.git
cd laundry-app

# 2. Install semua dependensi (PHP + JS + migrasi + build aset)
composer run setup
```

> Perintah `composer run setup` secara otomatis menjalankan: `composer install`, copy `.env`, generate key, migrasi database, `npm install`, dan `npm run build`.

### Konfigurasi Environment

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME="VIP Laundry"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_app
DB_USERNAME=root
DB_PASSWORD=
```

### Jalankan Aplikasi (Mode Development)

```bash
composer run dev
```

Perintah ini akan menjalankan secara bersamaan:
- `php artisan serve` — Web server
- `php artisan queue:listen` — Antrian notifikasi
- `php artisan pail` — Log monitoring
- `npm run dev` — Vite HMR

Akses aplikasi di: **`http://localhost:8000`**
Admin Panel di: **`http://localhost:8000/admin`**

---

## 👤 Akun Demo Default

> Akun dibuat otomatis oleh seeder saat menjalankan `php artisan migrate --seed`.

| Role | Email | Password | Akses Panel |
|---|---|---|---|
| **Admin** | `admin@laundry.com` | `password` | `/admin` |
| **Customer** | `customer@laundry.com` | `password` | `/customer/dashboard` |

---

## 📂 Struktur Direktori Utama

```
app/
├── Http/Controllers/
│   ├── Admin/          # Dashboard, Order, POS, Laporan, dll.
│   ├── Customer/       # Order & Review pelanggan
│   └── TrackingController.php
├── Models/             # User, Order, Service, Bundle, Promo, Review, dll.
├── Notifications/      # Notifikasi order baru & review
└── Console/Commands/   # Artisan command (auto-confirm order)

resources/views/
├── admin/              # Semua tampilan panel admin
├── customer/           # Tampilan portal pelanggan
└── welcome.blade.php   # Landing page publik

database/migrations/    # 13 file migrasi
```

---

## 🔄 Alur Status Order

```
pending → pickup → process → finished → delivered → completed
                                ↓ (otomatis setelah 24 jam jika tidak dikonfirmasi)
```

| Status | Keterangan |
|---|---|
| `pending` | Order baru masuk |
| `pickup` | Kurir sedang menjemput |
| `process` | Sedang dicuci |
| `finished` | Selesai dicuci, siap diantar |
| `delivered` | Sudah diantarkan ke pelanggan |
| `completed` | Pelanggan mengkonfirmasi penerimaan |

---

## 📝 Catatan

- **Poin Loyalitas:** Dihitung otomatis saat order mencapai status `finished` (1 poin per Rp 1.000).
- **Auto-Complete:** Order `delivered` yang tidak dikonfirmasi oleh pelanggan akan otomatis jadi `completed` setelah 24 jam via Artisan Command.
- **Geolocation:** Fitur deteksi lokasi pada POS memerlukan izin browser & koneksi internet (menggunakan OpenStreetMap).
- **Produksi:** Jalankan `php artisan config:cache && php artisan route:cache && php artisan view:cache` untuk performa optimal saat deploy.

---

Dibuat dengan ❤️ untuk kemudahan bisnis laundry Anda.
