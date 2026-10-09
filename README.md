# Serkom Kuliner: Sistem Pemesanan Makanan & Kasir Restoran

Aplikasi web pemesanan makanan (*Food Ordering & Cashier System*) berbasis **Laravel 11**, **Laravel Breeze**, **MySQL**, dan **Tailwind CSS**. Didesain khusus untuk memenuhi standar penilaian **Uji Kompetensi Keahlian (UKK) / Sertifikasi Kompetensi (Serkom LSP RPL)** dengan standar *zero-error* dan implementasi *best practices*.

---

## Fitur Utama

- **Master Catalog (Customer Side):**
  - Tampilan menu responsif dengan filter kategori instan (*Makanan*, *Minuman*, *Cemilan*).
  - Penghitungan subtotal transaksi secara *real-time*.
  - Modal konfirmasi pesanan interaktif sebelum submit data.
  - Transaksi atomik menggunakan `DB::transaction()` untuk menjamin integritas data multi-tabel (`orders` & `order_details`).

- **Admin Back-Office (Protected with Breeze):**
  - Dashboard pemantauan transaksi *real-time* dengan status pemesanan dinamis (*Pending*, *Diproses*, *Selesai*).
  - Manajemen CRUD Master Makanan lengkap (Upload gambar, validasi ketat, auto-cleanup gambar di storage saat update/delete).
  - Optimasi query database dengan *Eager Loading* (`Order::with('orderDetails.food')`) untuk mencegah isu *N+1 Query*.

---

## Panduan Instalasi Cepat

```bash
# 1. Clone repository
git clone https://github.com/lordbarry21/serkom-laravel-kuliner.git
cd serkom-laravel-kuliner

# 2. Salin environment & atur konfigurasi database
cp .env.example .env

# 3. Jalankan migrasi dan seeder awal
php artisan migrate:fresh --seed

# 4. Buat symbolic link untuk akses file gambar publik
php artisan storage:link

# 5. Jalankan server lokal
php artisan serve
```

Default Admin Account:
- **Email:** `admin@gmail.com`
- **Password:** `password123`
