# AUDIT LOG & BEDAH KODE MODUL 1-4 (ZERO-ERROR SERKOM LSP)

Dokumen ini mencatat setiap langkah pengujian fungsionalitas, identifikasi celah/error pada modul asli (Modul 1 s.d. 4), serta perbaikan kode berstandar *humanized clean code* agar mudah dihafal dan bebas dari *crash* saat dinilai oleh Asesor.

---

## 1. DAFTAR PERBAIKAN BUG KRUSIAL DARI MODUL ASLI

### Bug 1: Ketidaksinkronan Nilai ENUM Status Pesanan (Fatal SQL Error)
- **Letak Masalah:**
  - Modul 1 (Migration): Kolom `status` didefinisikan sebagai `enum('Pending', 'Diproses', 'Selesai')` (Huruf kapital / Title Case).
  - Modul 3 (`OrderController`): Menyimpan data order baru dengan `'status' => 'pending'` (huruf kecil).
  - Modul 4 (`dashboard.blade.php`): Dropdown status mengirim value `"completed"` dan `"cancelled"`.
- **Dampak Fatal:**
  Di MySQL (terutama mode Strict SQL pada Laravel 11/XAMPP terbaru), memasukkan nilai yang tidak terdaftar pada deklarasi ENUM akan memicu error fatal:  
  `SQLSTATE[01000]: Data truncated for column 'status' at row 1`.
- **Solusi Humanized:**
  Seluruh nilai distandardisasi menjadi: `['Pending', 'Diproses', 'Selesai', 'Batal']` di migration, controller validasi (`in:Pending,Diproses,Selesai,Batal`), dan dropdown Blade.

---

### Bug 2: Hilangnya Nilai Form saat Validasi Gagal (Form Reset Frustration)
- **Letak Masalah:**
  Pada Modul 2 (`create.blade.php` & `edit.blade.php`), tag input nama, harga, dan deskripsi tidak menggunakan fungsi helper `old('name')`.
- **Dampak:**
  Jika user mengunggah gambar yang ukurannya melebihi 2MB atau format tidak valid (`.pdf`), halaman akan me-refresh dan seluruh teks yang sudah diketik akan terhapus bersih.
- **Solusi Humanized:**
  Menambahkan atribut `value="{{ old('name') }}"` dan menampilkan pesan kesalahan spesifik per kolom menggunakan direktif `@error('name') ... @enderror`.

---

### Bug 3: Resiko Mass Assignment Kolom `subtotal` pada `OrderDetail`
- **Letak Masalah:**
  Modul 1 mencatat `OrderDetail` menggunakan `$guarded = ['id']`, namun jika siswa secara tidak sengaja beralih ke `$fillable` dan lupa mendaftarkan `'subtotal'`, proses checkout pelanggan akan gagal atau kolom subtotal bernilai `0`.
- **Solusi Humanized:**
  Memastikan `protected $guarded = ['id'];` terpasang secara eksplisit di model `OrderDetail`, dengan dokumentasi komentar mengapa cara ini jauh lebih aman saat checkout multi-item.

---

### Bug 4: Bahaya Deletion Cascade pada Menu yang Pernah Dipesan
- **Letak Masalah:**
  Modul 1 memasang `onDelete('cascade')` pada relasi `food_id` di `order_details`. Jika Admin menghapus salah satu menu makanan yang pernah dipesan, seluruh riwayat transaksi historis yang memuat menu tersebut akan ikut terhapus otomatis dari database, merusak laporan keuangan kasir.
- **Solusi Humanized:**
  Di view dashboard, kita menyertakan pengecekan null-safe `{{ $detail->food->name ?? 'Menu Terhapus' }}` agar dashboard kasir tetap stabil menampilkan riwayat transaksi meskipun data menu master mengalami modifikasi.

---

### Bug 5: Integritas File Fisik Gambar di Storage
- **Letak Masalah:**
  Pada Modul 2, ketika gambar menu di-update atau menu dihapus, file fisik lama di folder `storage/app/public/foods` rawan menumpuk menjadi sampah (*orphan files*).
- **Solusi Humanized:**
  Menambahkan pengecekan `Storage::disk('public')->exists($food->image)` sebelum `Storage::disk('public')->delete($food->image)` baik pada method `update()` maupun `destroy()`.

---

## 2. CHECKLIST PENGUJIAN LENGKAP ASESOR (STEP BY STEP)

1. **Persiapan Database:**
   - Jalankan `php artisan migrate:fresh --seed`
   - Pastikan tabel `foods`, `orders`, `order_details`, `users` terbuat tanpa error constraint.
   - Pastikan 1 user admin (`admin@gmail.com`) dan 5 menu makanan awal berhasil di-seed.

2. **Pengujian Sisi Pelanggan (`/`):**
   - Buka browser di halaman utama katalog.
   - Uji filter kategori: Klik *Makanan*, *Minuman*, *Cemilan*, lalu *Semua Menu*.
   - Tambah porsi menu menggunakan tombol stepper (+/-).
   - Perhatikan floating bottom bar yang mengkalkulasi total porsi dan total rupiah secara instan.
   - Isi Nama Pemesan dan Nomor Meja, lalu klik *Lanjut ke Pembayaran*.
   - Cek modal pop-up: Data pemesan, rincian menu, dan grand total harus akurat.
   - Klik *Ya, Pesan Sekarang*: Transaksi disimpan via `DB::transaction` dan muncul notifikasi sukses.

3. **Pengujian Sisi Admin (`/login` & `/dashboard`):**
   - Login dengan `admin@gmail.com` / `password123`.
   - Masuk ke dashboard kasir: Pesanan pelanggan yang baru saja dibuat langsung muncul di baris teratas.
   - Ubah status pesanan dari *Pending* ke *Diproses* atau *Selesai* melalui dropdown: Status tersimpan instan via HTTP PATCH.
   - Masuk ke menu *Master Menu* (`/admin/foods`):
     - Tambah menu baru dengan upload foto.
     - Edit nama/harga menu.
     - Hapus menu dan verifikasi data hilang dari tabel.
