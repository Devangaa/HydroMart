# Panduan Pull dan Menjalankan (Windows)

[![Kembali ke README Utama](https://img.shields.io/badge/Kembali-README%20Utama-22c55e?style=for-the-badge)](../README.md)

Panduan ini khusus untuk:
- pull update terbaru dari repository,
- setup project di laptop Windows lain,
- menjalankan project secara lokal.

---

## 1) Requirement

Pastikan sudah terpasang:
- Laragon (disarankan) atau XAMPP,
- Git,
- Composer,
- Node.js LTS.

Rekomendasi versi:
- PHP >= 8.1
- Composer >= 2.x
- Node.js >= 18

## 2) Clone Pertama Kali

```bash
cd C:\laragon\www
git clone <URL_REPOSITORY_KAMU> hydromart2
cd hydromart2
```

## 3) Install Dependency

```bash
composer install
npm install
```

## 4) Setup Environment

Salin file `.env`:
```bash
copy .env.example .env
```

Generate app key:
```bash
php artisan key:generate
```

Atur koneksi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hydromart2
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan juga konfigurasi berikut:
- `APP_URL`
- `MAIL_*` (untuk OTP email)
- `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION`
- `BINDERBYTE_API_KEY`

## 5) Siapkan Database

1. Buat database baru, contoh: `hydromart2`.
2. Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

## 6) Menjalankan Aplikasi

Terminal 1:
```bash
npm run dev
```

Terminal 2:
```bash
php artisan serve
```

Buka:
- `http://127.0.0.1:8000`
- atau domain Laragon (mis. `http://hydromart2.test`)

## 7) Cara Pull Update Terbaru

Jika project sudah ada:

```bash
cd C:\laragon\www\hydromart2
git branch
git pull origin <nama-branch>
```

Setelah pull, jalankan:

```bash
composer install
npm install
php artisan migrate
php artisan optimize:clear
```

Lalu jalankan lagi:

```bash
npm run dev
php artisan serve
```

## 8) Queue (Opsional)

Jika ada fitur yang memakai queue:

```bash
php artisan queue:work
```

## 9) Troubleshooting

Autoload error:
```bash
composer dump-autoload
```

Aset frontend tidak muncul:
```bash
npm run dev
```

Cache/config bermasalah:
```bash
php artisan optimize:clear
```

Reset database (hapus semua data):
```bash
php artisan migrate:fresh --seed
```

## 10) Catatan Tim

- Selalu `git pull` sebelum mulai coding.
- Gunakan branch terpisah untuk fitur/bugfix.
- Jangan commit file `.env`.
