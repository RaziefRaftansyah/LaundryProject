# LaundryProject — Website Laundry Coin

Laravel 13 + Tailwind CSS 4 + Alpine.js + Chart.js. Aplikasi untuk outlet laundromat **Laundry Coin** (Samarinda): laundromat modern dengan self-service cuci mandiri, drop-off kiloan, pembayaran cashless.

## Tujuan Website

1. **Etalase & informasi outlet** — tarif layanan, kapasitas mesin, jam buka, alamat & peta, kontak WA (halaman publik `/`).
2. **Pemesanan online pelanggan** — pelanggan memilih layanan, mengatur jumlah mesin, dan mengatur waktu antar cucian; transaksi dilunasi lewat Midtrans (Transfer Bank / E-Wallet QRIS) atau di kasir outlet.
3. **Lacak pesanan publik** — cek progres cucian hanya dengan kode pesanan dari struk kasir (tanpa login, rate-limited).
4. **Sistem operasional outlet** — kasir walk-in, shift kas harian, pengeluaran, void transaksi, laporan keuangan admin.
5. **Manajemen** — admin mengelola user, layanan/tarif, pesanan, refund.

## Alur Pengguna

### Pengunjung (tanpa akun)
```
Beranda /  →  lihat tarif, kapasitas mesin, jam buka (footer + section operasional), kontak WA
           →  /tracking?order_code=XXXX  →  status pesanan ( kode dari struk kasir )
           →  /tentang, /aturan-penggunaan
           →  /register → verifikasi email → masuk sebagai pelanggan
```

### Pelanggan (pemesanan online)
```
/pesan (Langkah 1)  →  pilih layanan (self / titip cuci), atur jumlah mesin, masuk keranjang
/keranjang          →  centang item, koreksi jumlah mesin
/pesan (Langkah 2)  →  detail & konfirmasi: nama, no. HP, tanggal antar, catatan
/checkout           →  buat transaksi → /pembayaran/{kode}
/pembayaran/{kode}  →  bayar online (Midtrans: Transfer / QRIS) atau bayar di kasir
   ↳ lunas          →  /pembayaran/{kode}/selesai  →  nota + status
   ↳ gagal/kadaluarsa →  coba bayar lagi / batal (refund otomatis atau manual oleh admin)
   ↳ dibayar lalu dibatalkan → pengajuan refund; admin menyelesaikannya di /perlu-refund
/orders              →  riwayat pesanan + detail + timeline status
/transaksi/{kode}    →  detail transaksi + batalkan
/akun                →  profil, riwayat, tautan Tentang Kami & Aturan
```

### Pelanggan — status pesanan

Transaksi (pembayaran): `menunggu_pembayaran → lunas | gagal | kadaluarsa | dibatalkan` (4 status terakhir = final).

Pesanan (pengerjaan): `pending → diproses → siap_diambil → selesai`. Pesanan boleh diproses sebelum lunas, tetapi hanya boleh ditandai selesai setelah transaksi lunas. Mundur status hanya admin, dengan catatan wajib, dan hanya dalam jendela koreksi setelah "selesai".

### Pegawai outlet (walk-in kasir)
```
/pegawai/dashboard        →  ringkasan
/pegawai/orders           →  buat pesanan walk-in, update tahapan cuci/kering/lipat
/pegawai/keuangan         →  rekap keuangan (read-only)
/panel/transaksi          →  transaksi kasir: buat, lunasi di kasir, struk, void
/panel/transaksi/baru     →  kasir walk-in (pilih pelanggan / tambah cepat)
/panel/shift              →  buka/tutup shift kas harian
/panel/pengeluaran        →  catat pengeluaran operasional
/panel/pelanggan          →  cari/daftarkan pelanggan
```

### Admin
```
/admin/dashboard          →  statistik pesanan 6 bulan terakhir
/admin/orders             →  semua pesanan, konfirmasi pembayaran, cek Midtrans
/admin/services           →  kelola layanan & tarif
/admin/users              →  kelola user
/admin/perlu-refund       →  nomor perlu refund + refund pembatalan
/panel/laporan            →  laporan keuangan (trend, saluran, metode)
/panel/ekspor             →  ekspor laporan
/panel/tutup-buku         →  tutup periode
/panel/void               →  setujui/tolak void
/panel/shift, /panel/pengeluaran, /panel/pelanggan  →  verifikasi shift, pengeluaran, pelanggan
```

## Peta Rute Utama

| Alamat | Akses | Fungsi |
|--------|-------|--------|
| `/` | publik | Landing: tarif, jam buka, kontak, peta |
| `/tracking` | publik (rate-limited) | Lacak pesanan via kode |
| `/register`, `/login` | publik | Akun pelanggan |
| `/pesan`, `/keranjang`, `/pembayaran/{kode}` | pelanggan | Pemesanan & pembayaran |
| `/orders`, `/transaksi/{kode}`, `/akun` | pelanggan | Riwayat & profil |
| `/pegawai/*` | pegawai | Kasir walk-in & operasional |
| `/panel/*` | admin + pegawai | Kasir harian, shift, laporan |
| `/admin/*` | admin | Manajemen user, layanan, refund |
| `/payments/webhook/{gateway}` | gateway | Notifikasi pembayaran (signature, tanpa CSRF) |

## Konfigurasi Outlet

Data outlet diubah di `config/laundry.php` (atau `.env`):

- **Telepon/WA**: `LAUNDRY_PHONE` / `LAUNDRY_WHATSAPP` — nilai aktif `0821-5030-5758`.
- **Jam operasional**: Senin–Jumat `07.00 - 19.00`, Sabtu–Minggu `07.00 - 21.00` WITA.
- **Layanan & kapasitas mesin**: `item_types` (pakaian 6 KG, sprei 3 KG, bedcover 1 Pcs, campuran 3 KG per mesin).
- **Metode pembayaran online**: `online_payment_methods` (Transfer Bank, E-Wallet / QRIS).

Nilai-nilai ini dibagikan ke semua view via `View::share` di `AppServiceProvider` — ubah di satu tempat, berlaku di seluruh UI. Placeholder `[ISI ...]` otomatis disembunyikan oleh `App\Support\Placeholder`.

## Teknis Singkat

| Aspek | Nilai |
|-------|-------|
| Framework | Laravel 13, PHP 8.3 |
| Frontend | Tailwind CSS 4 (`@theme` di `resources/css/app.css`), Alpine.js, Chart.js (lazy-load) |
| Data outlet | `config/laundry.php` + `.env` |
| Warna diagram | `--color-chart-*` di `app.css`, dibaca JS via `getChartColors()` |
| Desain | Lihat `DESIGN.md` |
| Audit UI | Lihat `anti-slop/audit-001-2026-11-12.md` |
| Migrasi DB | `php artisan migrate` |
| Server dev | `composer dev` (server + queue + vite) atau `php artisan serve` |
| Build | `npm run build` |

## Peran & Hak Akses

Tiga peran (`App\Enums\UserRole`): `admin`, `pegawai`, `pelanggan`. Hak aksi per peran didaftarkan di `config/permissions.php` dan dieksekusi sebagai Gate native (`can:transaksi.void.approve`, dst.). Pelanggan hanya melihat data miliknya sendiri; transaksi orang lain → 404.
