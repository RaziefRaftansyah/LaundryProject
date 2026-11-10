# Laundry Coin

## Ringkasan
Aplikasi laundry Laravel Blade MVC dengan area publik, pelanggan, pegawai, dan admin. Fitur utama meliputi katalog layanan, keranjang, checkout, pembayaran, tracking order, antrean pengerjaan, laporan, refund, dan manajemen outlet.

## Perubahan yang diterapkan

### Design system mobile-first
Palet warna wajib didefinisikan di `resources/css/app.css`:

```css
:root {
  --brand:#ED1E24; --primary:#D91B20; --primary-hover:#B71419; --primary-dark:#8F0F13;
  --tint:#FDECEC; --surface:#FFF5F5; --text:#1F1415; --text-muted:#5C4A4B;
  --accent-orange:#B34700; --accent-magenta:#C2006F; --success:#1B7F3B;
}
```

CTA utama memakai `#D91B20`, hover `#B71419`, heading/fokus `#8F0F13`, sukses `#1B7F3B`, proses `#B34700`, dan self-service `#C2006F`. Status selalu memakai ikon dan teks. Target sentuh minimal 48px dan fokus keyboard menggunakan outline 2px dengan offset 2px.

Semua penggunaan warna biru pada Blade/CSS yang telah ditemukan diarahkan ke palet brand.

### Beranda
- Eyebrow badge di atas headline dihapus.
- Kartu ilustrasi mesin cuci diganti iframe Google Maps Laundry Coin.
- Peta menggunakan embed Google Maps pada `resources/views/components/sections/hero.blade.php`.
- Tombol lokasi menggunakan `https://maps.app.goo.gl/ut37zj8Xg8pxSAYi9`.

### Navigasi mobile
Bottom navigation tampil pada layar mobile dan berisi lima pilihan, urutan kiri ke kanan:

1. Beranda
2. Transaksi
3. Tombol bulat `+ Pesan`
4. Keranjang
5. Akun

Urutan mulai dari kanan: Akun, Keranjang, Pesan, Transaksi, Beranda. Menu hamburger tidak mengulang lima tujuan tersebut; hanya memuat tautan informasi seperti Layanan, Cara Kerja, Area & Jam, FAQ, dan Kontak.

### Antrean pegawai
Dashboard pegawai mengelompokkan proses berdasarkan order dan hanya menampilkan proses aktif berikutnya dengan urutan:

`cuci → kering → lipat`

Tombol aksi menyesuaikan proses:

- `Tandai Cuci Selesai`
- `Tandai Kering Selesai`
- `Tandai Lipat Selesai`

Route backend tetap sama; perubahan hanya pada cara proses aktif dipilih dan label tombol.

### Login nomor HP
Login sekarang menggunakan nomor HP/WhatsApp dan kata sandi, bukan email. Nomor diterima dalam format lokal Indonesia dan dinormalisasi sebelum dicari pada kolom `users.phone` yang sudah ada.

Registrasi:
- Nomor HP wajib.
- Email opsional.
- Email placeholder dibuat otomatis bila email dikosongkan agar kompatibel dengan kolom email database.
- Nomor HP yang disimpan pada akun otomatis digunakan saat checkout, sehingga pelanggan tidak perlu mengisi nomor kontak lagi.

Perubahan auth berada di `LoginRequest.php`, `RegisteredUserController.php`, dan form auth. Tidak ada penambahan kolom database pada reapply ini.

### Informasi outlet
Default konfigurasi berada pada `config/laundry.php`:

- Alamat: `Jl. PM. Noor No.15b, Sempaja Sel., Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119`
- Telepon: `0821-5030-5758`
- Maps: `https://maps.app.goo.gl/ut37zj8Xg8pxSAYi9`
- Senin–Jumat: `07:00 - 19:00 WITA`
- Sabtu–Minggu: `07:00 - 20:00 WITA`
- Kontak otomatis menggunakan `tel:+6282150305758`.

## Alur aplikasi
1. Pengunjung membuka `/`.
2. Pelanggan mendaftar/login dengan nomor HP.
3. Pelanggan memilih layanan pada `/pesan`.
4. Item disimpan ke `/pesan/keranjang`.
5. Checkout dibuat pada `/pembayaran/{checkout:code}`.
6. Pembayaran diproses gateway dan status dipantau.
7. Pegawai memproses order berurutan cuci, kering, lalu lipat.
8. Pelanggan melihat status melalui riwayat, timeline, atau `/tracking`.

## Validasi
```bash
php artisan route:list
php artisan view:cache
php -l app/Http/Requests/Auth/LoginRequest.php
php -l app/Http/Controllers/Auth/RegisteredUserController.php
php artisan test
npm run build
```

`php artisan view:cache` dan lint PHP telah berhasil dijalankan setelah reapply. Jika `npm run build` mengalami `spawn EPERM`, itu berasal dari pembatasan proses `esbuild` pada environment Windows.
