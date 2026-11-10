# Dokumentasi Pengembangan Laundry Coin

Dokumen ini ditujukan untuk pengembang, maintainer, administrator sistem, dan pihak lain yang perlu memahami struktur teknis proyek.

## Stack dan struktur

- Framework: Laravel Blade MVC.
- Frontend: Blade, Tailwind CSS, Alpine.js, dan Vite.
- Backend: Controller, Form Request, Service, Policy, Model, dan Migration.
- Route utama: `routes/web.php`.
- Controller: `app/Http/Controllers`.
- Service/domain: `app/Services`.
- Model: `app/Models`.
- Database migration: `database/migrations`.
- View dan komponen UI: `resources/views`.
- CSS utama: `resources/css/app.css`.
- Konfigurasi outlet: `config/laundry.php`.

## Area aplikasi

### Publik

- `/`: landing page, layanan, peta lokasi, jam operasional, FAQ, dan kontak.
- `/tracking`: tracking pesanan publik berdasarkan kode order dengan data minimal.
- `/tentang`: informasi Laundry Coin.
- `/aturan-penggunaan`: aturan penggunaan layanan.

### Pelanggan

- `/akun`: profil, riwayat, dan status pesanan.
- `/pesan`: katalog layanan dan form pemesanan.
- `/pesan/keranjang`: pengelolaan keranjang.
- `/pembayaran/{checkout:code}`: checkout dan pembayaran.
- `/pelanggan/orders`: daftar dan detail pesanan.

### Pegawai

- `/pegawai/dashboard`: ringkasan operasional dan antrean proses.
- `/pegawai/orders`: order walk-in dan perubahan proses.
- `/pegawai/keuangan`: ringkasan serta ekspor keuangan.
- `/panel`: transaksi, shift, pelanggan, pengeluaran, void, dan laporan sesuai permission.

### Admin

- `/admin/dashboard`: ringkasan admin.
- Manajemen user, layanan, order, pembayaran, refund, laporan, ekspor, tutup buku, dan persetujuan void.

## Alur domain

1. Pelanggan memilih layanan dan menyimpannya ke keranjang.
2. Checkout membuat satu transaksi yang dapat berisi beberapa order.
3. Pembayaran diproses melalui gateway dan webhook.
4. Setelah lunas, order masuk ke antrean pengerjaan.
5. Proses dikerjakan berurutan: `cuci` → `kering` → `lipat`.
6. Dashboard pegawai hanya menampilkan satu proses aktif berikutnya untuk setiap order.
7. Pelanggan melihat perubahan status melalui riwayat dan timeline.

## Antrean pegawai

View `resources/views/pegawai/dashboard.blade.php` mengelompokkan `OrderProcess` berdasarkan `order_id`, lalu memilih proses pending dengan prioritas:

```text
cuci = 1
kering = 2
lipat = 3
```

Tombol aksi tetap mengirim `status=selesai` ke route backend yang sama, dengan label yang mengikuti proses aktif:

- `Tandai Cuci Selesai`
- `Tandai Kering Selesai`
- `Tandai Lipat Selesai`

## Autentikasi nomor HP

`LoginRequest` menerima nomor HP dan kata sandi. Nomor dinormalisasi melalui `App\Support\PhoneNumber`, kemudian dicari pada kolom `users.phone`. Dukungan email tetap dipertahankan sebagai fallback kompatibilitas untuk akun dan test lama.

Pada registrasi:

- Nomor HP dapat dinormalisasi dan disimpan dalam format lokal.
- Email dapat dikosongkan pada form baru.
- Email placeholder dibuat bila dibutuhkan oleh skema database lama.
- Role publik selalu dipaksa menjadi `pelanggan`.

## Sistem warna

Skala primary tersedia di `resources/css/app.css`:

```css
--primary-50:#FEF2F2;
--primary-100:#FFE1E2;
--primary-200:#FFC8C9;
--primary-300:#FFA2A4;
--primary-400:#FD6C70;
--primary-500:#F63D42;
--primary-600:#D91B20;
--primary-700:#BF161A;
--primary-800:#9E161A;
--primary-900:#83191C;
--primary-950:#47080A;
```

Warna semantic:

- Sukses: `#22C55E`.
- Gagal: `#47080A`.
- Informasi: `#3B82F6`.
- Surface netral: `#FAFAFA`.
- Teks utama: `#1F1415`.
- Teks pendukung: `#5C4A4B`.

Gradien di hero beranda dan dashboard pegawai telah dihapus. Komponen menggunakan Spatial System dengan surface, border, radius, spacing, dan shadow yang konsisten.

## Komponen UI penting

- `resources/views/components/button.blade.php`: variasi tombol dan target sentuh.
- `resources/views/components/badge.blade.php`: badge status dengan ikon dan teks.
- `resources/views/components/bottom-nav.blade.php`: bottom navigation mobile.
- `resources/views/components/navbar.blade.php`: navbar publik dan menu informasi.
- `resources/views/components/sections/hero.blade.php`: hero, jam operasional, dan peta.
- `resources/views/components/sections/area.blade.php`: informasi lokasi dan jadwal outlet.
- `resources/views/components/footer.blade.php`: footer publik tanpa badge Mesin Siap Pakai dan Pembayaran Cashless.
- `resources/views/pelanggan/pesan/partials/service-form.blade.php`: form layanan dengan radio button jenis cucian.

## Konfigurasi outlet

Nilai default berada di `config/laundry.php` dan dapat dioverride melalui `.env`:

```env
LAUNDRY_ADDRESS="Jl. PM. Noor No.15b, Sempaja Sel., Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119"
LAUNDRY_PHONE=0821-5030-5758
LAUNDRY_WHATSAPP=6282150305758
LAUNDRY_MAPS_URL=https://maps.app.goo.gl/ut37zj8Xg8pxSAYi9
LAUNDRY_HOURS_WEEKDAY="07:00 - 19:00"
LAUNDRY_HOURS_WEEKEND="07:00 - 20:00"
LAUNDRY_TIMEZONE_LABEL=WITA
```

## Validasi lokal

```bash
php artisan route:list
php artisan view:cache
php -l app/Support/PhoneNumber.php
php -l app/Http/Requests/Auth/LoginRequest.php
php -l app/Http/Controllers/Auth/RegisteredUserController.php
php artisan test
npm run build
```

`npm run build` dapat mengalami `spawn EPERM` pada environment Windows tertentu ketika Vite menjalankan esbuild. Jika terjadi, periksa izin proses Node/esbuild sebelum menyimpulkan adanya error kode.

## Dokumentasi lanjutan

Dokumentasi database dan controller tersedia di [`docs/DOKUMENTASI_DATABASE_DAN_CONTROLLER.md`](DOKUMENTASI_DATABASE_DAN_CONTROLLER.md).
