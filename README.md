# HydroMart 2

> Sistem e-commerce Laravel untuk penjualan **produk** dan **layanan** dengan fitur checkout, pembayaran, tracking, dan reward pelanggan.

[![Panduan Pull & Menjalankan](https://img.shields.io/badge/Lihat-Panduan%20Pull%20%26%20Menjalankan-0ea5e9?style=for-the-badge)](docs/RUNNING_GUIDE_WINDOWS.md)

---

## Tentang Sistem

HydroMart 2 dirancang untuk mendukung alur bisnis penjualan end-to-end:
- autentikasi pengguna (register, login, forgot password OTP),
- katalog produk dan layanan,
- keranjang, checkout, dan ongkir,
- pembayaran online (Midtrans) + COD,
- tracking pengiriman,
- reward poin dan penukaran reward,
- dashboard admin untuk manajemen operasional.

## Visual Arsitektur Alur

```mermaid
flowchart LR
    A[Pelanggan] --> B[Frontend Laravel Blade]
    B --> C[Controller]
    C --> D[(MySQL)]
    C --> E[Midtrans API]
    C --> F[BinderByte API]
    C --> G[Mail Service OTP]
    H[Admin] --> B
```

## Peta Modul Utama

| Modul | Fungsi Inti | Aktor |
|---|---|---|
| Authentication | Login, Register, OTP Reset Password | Pelanggan/Admin |
| Katalog Produk | Lihat produk, detail, ulasan | Pelanggan |
| Katalog Layanan | Lihat layanan, detail, ulasan | Pelanggan |
| Checkout & Transaksi | Checkout produk/layanan, ongkir, status order | Pelanggan |
| Payment Gateway | Pembuatan pembayaran, callback Midtrans | Sistem |
| Reward | Klaim reward dari poin, validasi masa berlaku | Pelanggan/Admin |
| Admin Panel | Kelola produk/layanan/reward/transaksi/ulasan | Admin |

## Alur Pengguna (Customer Journey)

```mermaid
flowchart TD
    A[Register / Login] --> B[Browse Produk / Layanan]
    B --> C[Tambah ke Keranjang / Buy Now]
    C --> D[Checkout]
    D --> E[Pilih Pembayaran]
    E --> F{COD atau Online}
    F -->|Online| G[Bayar via Midtrans]
    F -->|COD| H[Menunggu Konfirmasi]
    G --> I[Menunggu Konfirmasi]
    H --> I
    I --> J[Diproses]
    J --> K[Dikirim]
    K --> L[Selesai]
    L --> M[Ulasan + Poin Reward]
```

## Peran Sistem

### Pelanggan
- Kelola akun & password.
- Belanja produk/layanan.
- Checkout dan pilih metode pembayaran.
- Pantau status transaksi dan tracking.
- Klaim reward dan lihat riwayat penukaran.

### Admin
- Kelola produk dan layanan (CRUD + soft delete/restore).
- Kelola reward.
- Validasi dan update status transaksi.
- Input resi, pantau pengiriman, balas ulasan.

## Integrasi Eksternal

- **Midtrans**: pembuatan transaksi pembayaran dan callback status pembayaran.
- **BinderByte**: validasi serta tracking resi pengiriman.
- **Mail/SMTP**: pengiriman OTP saat reset password.

## Galeri Tampilan (Tempat Screenshot)

> Ganti gambar placeholder berikut dengan screenshot asli web kamu.

### 1) Halaman Beranda
![Screenshot Beranda](docs/assets/screenshots/01-beranda.png)

### 2) Halaman Detail Produk
![Screenshot Detail Produk](docs/assets/screenshots/02-detail-produk.png)

### 3) Halaman Keranjang
![Screenshot Keranjang](docs/assets/screenshots/03-keranjang.png)

### 4) Halaman Checkout
![Screenshot Checkout](docs/assets/screenshots/04-checkout.png)

### 5) Halaman Pembayaran
![Screenshot Pembayaran](docs/assets/screenshots/05-pembayaran.png)

### 6) Dashboard Admin
![Screenshot Dashboard Admin](docs/assets/screenshots/06-dashboard-admin.png)

## Struktur Folder Penting

```text
app/Http/Controllers/
├── Auth/        # Login, register, forgot password
├── Admin/       # Manajemen data operasional
├── Pelanggan/   # Keranjang, transaksi, ulasan, reward
└── Api/         # Callback Midtrans
```

## Dokumentasi Operasional

- Panduan pull repository dan cara menjalankan di Windows:
  - [docs/RUNNING_GUIDE_WINDOWS.md](docs/RUNNING_GUIDE_WINDOWS.md)
