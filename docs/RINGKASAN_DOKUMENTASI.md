# Ringkasan Dokumentasi — Laundry Coin

> Dokumen ini merangkum `README.md`, `docs/DOKUMENTASI_DATABASE_DAN_CONTROLLER.md`, `docs/KEUANGAN.md`, dan
> `docs/PEMBAYARAN.md` menjadi satu peta kerja. Tujuan: saat ingin mengubah fitur, langsung tahu **file mana**
> yang disentuh dan **aturan mana** yang tidak boleh dilanggar.
>
> Diverifikasi terhadap kode per 12 November 2026. Jika dokumen lama berbeda dengan dokumen ini, **kode + dokumen
> ini yang menjadi acuan** (lihat bagian 8).

---

## 1. Gambaran sistem

| Aspek | Kenyataan di kode |
|---|---|
| Framework | Laravel 13, PHP ^8.3; frontend Blade + Alpine.js + Chart.js, Tailwind 4 + Vite |
| Database | MySQL, database `Sistem-Manajemen-Laundry` |
| Session/cache/queue | `database` |
| Role | **3 role**: `admin`, `pegawai`, `pelanggan` (`App\Enums\UserRole`) |
| Area aplikasi | Publik (`/`), Pelanggan (`/pesan`, `/keranjang`, `/pembayaran/{kode}`, `/akun`), Panel admin+pegawai (`/panel/*`), Admin (`/admin/*`), Auth (`/login`, `/register`) |
| Saluran transaksi | `online` (keranjang pelanggan, kode `TRX-…`) dan `outlet` (kasir, kode `OTL-…`) |
| Pembayaran online | Midtrans Snap, di balik flag `PAYMENT_ENABLED` (default mati) |
| Uang | Selalu **integer rupiah**; tidak ada float di perhitungan |
| Zona waktu bisnis | `config('laundry.timezone')` (`LAUNDRY_TIMEZONE`, default `Asia/Jakarta`); DB menyimpan UTC |

**Catatan penting:** dokumentasi lama menyebut role `operator`/`kurir` dan modul `deliveries`, `order_items`,
`user_addresses`. Itu **sudah tidak ada** di kode saat ini (lihat bagian 8).

---

## 2. Peta “mau ubah X, sentuh file Y”

| Domain / fitur | Route | Controller | Service / logika | Model |
|---|---|---|---|---|
| Landing & katalog | `/` | `HomeController` | — | `Service` |
| Tracking publik | `/tracking` | `OrderTrackingController` | — | `Order` |
| Keranjang & checkout online | `/pesan`, `/pesan/keranjang`, `/pesan/checkout` | `Pelanggan\CartController`, `Pelanggan\CheckoutController` | `CartService`, `CheckoutService`, `OrderService` | `CartItem`, `Checkout`, `Order` |
| Pembayaran Snap | `/pembayaran/{kode}/*` | `Pelanggan\CheckoutController` | `SnapPaymentService`, `PaymentService`, `OrderStateMachine` | `Checkout`, `Payment` |
| Webhook gateway | `POST /payments/webhook/{gateway}` | `PaymentWebhookController` | `MidtransNotificationService` | `PaymentNotificationLog`, `PaymentEvent` |
| Pembatalan & refund online | `/pelanggan/transaksi/{kode}/batal`, `/panel/perlu-refund/*` | `Pelanggan\TransactionController`, `Admin\RefundController` | `OnlineCancellationService`, `RefundService` | `Refund`, `Payment` |
| Dashboard admin | `/admin/dashboard` | `Admin\DashboardController` | query langsung | semua |
| Kelola user | `/admin/users` | `Admin\UserController` | — | `User` |
| Kelola order (admin) | `/admin/orders` | `Admin\OrderController` | `OrderService`, `OrderStateMachine` | `Order`, `OrderProcess`, `OrderStatusLog` |
| Kelola layanan | `/admin/services` | `Admin\ServiceController` | — | `Service` |
| Kasir / transaksi outlet | `/panel/transaksi` | `Panel\TransactionController`, `Panel\VoidController` | `OutletTransactionService`, `VoidService` | `Checkout`, `Order`, `VoidRequest` |
| Pelanggan (panel) | `/panel/pelanggan` | `Panel\CustomerController` | `CustomerDirectory`, `CustomerService` | `User` |
| Shift / kas harian | `/panel/shift` | `Panel\ShiftController` | `ShiftService` | `CashShift` |
| Pengeluaran | `/panel/pengeluaran` | `Panel\ExpenseController` | `ExpenseService` | `Expense`, `ExpenseCategory` |
| Laporan keuangan | `/panel/laporan` | `Panel\ReportController` | `FinancialReportService`, `LedgerService` | `FinancialEntry` |
| Export Excel | `/panel/ekspor` | `Panel\ExportController` | `FinanceExportService`, `FinanceExcelExporter`, `GenerateFinanceExport` | `FinanceExport` |
| Tutup buku | `/panel/tutup-buku` | `Panel\PeriodController` | `ClosedPeriodService` | `ClosedPeriod` |
| Void approval | `/panel/void` | `Panel\VoidController` | `VoidService` | `VoidRequest` |
| Dashboard pegawai | `/pegawai/dashboard` | `Pegawai\DashboardController` | — | — |
| Order pegawai (legacy) | `/pegawai/orders` | `Pegawai\OrderController` | `OrderService` | `Order` |
| Akun pelanggan | `/akun` | `Pelanggan\AccountController` | `CustomerOrderHistory` | `Checkout`, `Order` |
| Auth | `/login`, `/register`, dst. | `Auth\*` | — | `User` |

**Konfigurasi terpusat yang sering dipakai:**
`config/laundry.php` (identitas toko, item type, jam operasional, timezone) ·
`config/finance.php` (biaya gateway, refundable types, metode outlet, pecahan uang, limit export) ·
`config/payment.php` (Midtrans, expiry) · `config/permissions.php` (satu-satunya peta permission per role).

**Design token warna:** semua warna brand dan info dideklarasikan di `resources/css/app.css` dalam blok `@theme`:
- `--color-primary-50` … `--color-primary-950` → warna brand (sekarang merah). Ganti shade di sini untuk mengubah seluruh CTA, link, badge, dan state aktif.
- `--color-info-50` … `--color-info-900` → biru khusus kartu/banner informasi (tutorial, alert info, status proses). Sengaja dipisah agar tidak ikut berubah saat brand diganti.
- Netral 60% memakai palet `slate` Tailwind (latar `bg-slate-50`, teks `slate-900`, border `slate-200`).
- Jangan menulis hex langsung di Blade; gunakan kelas token (`bg-primary-700`, `bg-info-50`, `text-info-800`, dst.).

---

## 3. Alur bisnis ringkas

### 3.1 Checkout online
```
keranjang → checkout (submission_token, harga dihitung server)
  → checkouts.status = menunggu_pembayaran (kode TRX-…, batas bayar)
  → POST bayar → payments {kode}-P{n} (pending, maks 1) → Midtrans Snap
  → webhook / reconcile / admin cek status → checkouts.status = lunas
  → pesanan dikerjakan
```

Status checkout: `menunggu_pembayaran → lunas | gagal | kadaluarsa | dibatalkan` (4 status terakhir final).

### 3.2 Pesanan
```
orders.status: pending → diproses → siap_diambil → selesai
```
- Hanya admin/pegawai, hanya setelah transaksi lunas. Boleh melompat maju.
- `selesai` final untuk pegawai; admin boleh koreksi mundur dengan catatan (dari `selesai` maks 24 jam).
- Setiap perubahan dicatat di `order_status_logs` (`actor_type`: pelanggan/admin/pegawai/webhook/reconcile/scheduler/system).

### 3.3 Kasir outlet
```
/panel/transaksi/baru → bayar sekarang (tunai wajib shift aktif) atau "bayar saat ambil"
  → lunas → entri ledger `sale`
  → koreksi setelah lunas hanya lewat VOID (pengajuan pegawai → approval admin)
```
Satu order = satu layanan; satu checkout = beberapa order; satu tagihan untuk semuanya.

### 3.4 Ledger keuangan
Laporan **hanya** membaca `financial_entries` (append-only), ditulis di transaksi DB yang sama dengan kejadiannya.

| entry_type | Kapan | Arah |
|---|---|---|
| `sale` | Pembayaran `paid` (webhook, Midtrans, kasir, pelunasan, lunas manual) | + |
| `gateway_fee` | Bersamaan sale Midtrans (estimasi `flat + amount×bps/10000`) | − |
| `void_reversal` | Void disetujui admin | − |
| `refund` | Refund pembatalan online selesai | − |
| `expense` | Pengeluaran disetujui admin | − |

`UNIQUE(entry_type, source_type, source_id)` mencegah entri ganda. Neto = bruto + void + refund + fee.
Laba = neto + pengeluaran (hanya tab Gabungan).

---

## 4. Aturan invarian (jangan dilanggar saat mengubah kode)

1. **Uang integer rupiah.** Jangan pakai float/desimal untuk perhitungan; persentase dalam basis poin.
2. **Ledger append-only.** Jangan update/delete `financial_entries` maupun `audit_logs`; koreksi = entri baru.
3. **1 tagihan pending per transaksi.** Dijaga UNIQUE `pending_owner`; percobaan baru = `-P{n+1}`.
4. **Status hanya lewat `OrderStateMachine`** agar transisi valid dan tercatat.
5. **`channel` ditentukan server.** Field `channel` dari request tidak masuk `validated()`.
6. **Harga disalin saat transaksi** (`unit_price`, `service_name_snapshot`) agar riwayat tidak berubah.
7. **Tutup buku mengunci tanggal bisnis.** Void/refund manual/pengeluaran di periode tertutup ditolak; uang yang
   benar-benar masuk dari gateway tetap diakui di tanggal hari ini.
8. **Lunas hanya dari gateway** saat `PAYMENT_ENABLED=true` (webhook, reconcile, cek status admin). Halaman
   pembayaran hanya memverifikasi, tidak pernah mengubah status.
9. **Webhook idempoten**: simpan notifikasi → verifikasi signature SHA512 → cek API status → dedup
   `{transaction_id}:{status}`. Signature salah = 403; API tidak bisa dihubungi = 503 (Midtrans kirim ulang).
10. **Export harus seimbang.** Sheet Kontrol wajib selisih 0; jika tidak, export gagal dan tidak diterbitkan.
11. **Akun nonaktif tidak punya permission dan tidak bisa login.**
12. **Business logic penghapusan:** layanan yang masih dipakai order tidak boleh dihapus; admin tidak boleh
    menghapus akunnya sendiri.

---

## 5. Konfigurasi & environment penting

| Variabel | Default | Fungsi |
|---|---|---|
| `PAYMENT_ENABLED` | `false` | Saklar utama Midtrans |
| `MIDTRANS_IS_PRODUCTION` | `false` | Sandbox vs production |
| `MIDTRANS_SERVER_KEY` / `MIDTRANS_CLIENT_KEY` | — | Server key **rahasia**; client key boleh tampil |
| `MIDTRANS_HTTP_TIMEOUT` | `15` | Timeout request Midtrans (detik) |
| `MIDTRANS_IN_FLIGHT_SECONDS` | `45` | Tagihan tanpa snap_token dianggap macet |
| `MIDTRANS_ENABLED_PAYMENTS` | VA, echannel, gopay, shopeepay, qris | Kartu kredit sengaja mati |
| `PAYMENT_EXPIRY_MINUTES` | `60` | Batas bayar + expiry Snap |
| `ONLINE_CANCEL_WINDOW_MINUTES` | `15` | Jendela batal setelah bayar |
| `LAUNDRY_TIMEZONE` | `Asia/Jakarta` | Tanggal bisnis ledger/shift/laporan |
| `LAUNDRY_STAFF_WHATSAPP` | WA outlet | Nomor WA di nota |
| `FINANCE_EXPORT_SYNC_ROWS` | `5000` | Di atas ini export lewat queue |
| `EXPENSE_RECEIPT_MAX_KB` | `4096` | Ukuran maks nota |

Setelah mengubah `.env` di server ber-cache: `php artisan config:clear` (atau `config:cache` ulang).

---

## 6. Operasional & command

```bash
php artisan migrate
php artisan test

# Keuangan
php artisan ledger:backfill --dry-run   # cek pembayaran lunas tanpa entri
php artisan ledger:backfill             # idempoten
php artisan ledger:verify               # 0 selisih = exit 0

# Pembayaran (tiap 5 menit, withoutOverlapping)
php artisan payments:reconcile          # cocokkan tagihan pending ke Midtrans
php artisan checkouts:expire --grace=5  # tutup transaksi lewat batas bayar

# Scheduler & queue
php artisan schedule:work               # lokal (Windows)
php artisan queue:work --tries=3 --max-time=3600
```

Production: cron `* * * * * php artisan schedule:run`, queue worker via Supervisor/systemd,
`php artisan queue:restart` setiap deploy.

---

## 7. Checklist perubahan aman

1. Tentukan domain di bagian 2 → baca controller + service terkait (logika bisnis ada di `app/Services`, bukan controller).
2. Jika menyentuh status/pembayaran: cek `OrderStateMachine`, `PaymentService`, `SnapPaymentService`.
3. Jika menyentuh uang: pastikan integer, tulis lewat `LedgerService`, dan `php artisan ledger:verify` harus 0.
4. Jika menambah route panel: pasang `can:<permission>` dan daftarkan permission di `config/permissions.php`.
5. Jika menambah kolom: migration aditif + perbarui model/factory + cek `tests/Feature/DatabaseSchemaTest.php`.
6. Jalankan tes relevan, minimal:
   `tests/Feature/CheckoutFlowTest`, `PaymentFlowTest`, `MidtransWebhookTest`, `OrderStateMachineTest`,
   `CashierOrderTest`, `Finance/FinanceEndToEndTest`, `Finance/FinanceExportTest`, `RoleAccessVerificationTest`,
   `BackendArchitectureTest`.
7. Uji dengan **`PAYMENT_ENABLED=false` dan `true`** bila menyentuh pembayaran.
8. Perbarui dokumen ini/dokumen terkait bila aturan atau peta file berubah.

---

## 8. Inkonsistensi dokumen lama (penting)

`docs/DOKUMENTASI_DATABASE_DAN_CONTROLLER.md` sudah **ketinggalan versi** dan tidak boleh dipakai sebagai acuan
skema. Perbedaannya:

| Dokumen lama | Kode saat ini |
|---|---|
| Laravel 11 + Inertia.js | Laravel 13, Blade + Alpine.js (tanpa Inertia/React) |
| Role `admin`, `operator`, `kurir`, `pelanggan` | `admin`, `pegawai`, `pelanggan` |
| Tabel `order_items`, `deliveries`, `user_addresses` | Tidak ada (order langsung menyimpan `service_id`, `machine_qty`, `unit_price`, `item_type`) |
| `orders` punya `weight`, `pickup_address`, `delivery_address` | Tidak ada; ada `checkout_id`, `order_code`, `customer_name/phone`, `notes` |
| Route `/admin/manajemen-users`, `/admin/manajemen-order`, `/admin/layanan-laundry`, `/admin/pickup-delivery` | `/admin/users`, `/admin/orders`, `/admin/services`; tidak ada modul pickup-delivery |
| `AdminDashboardController` saja | `Admin\DashboardController` (alias `AdminDashboardController` di routes); `AdminDashboardController` root hanya shim tak terpakai |
| Status order `pending/diproses/selesai/diantar` | `pending/diproses/siap_diambil/selesai` |
| Tidak menyebut `checkouts`, `payments` transaksi, ledger, shift | Semua ada dan menjadi inti alur |

`docs/KEUANGAN.md` dan `docs/PEMBAYARAN.md` **selaras** dengan kode dan tetap menjadi rujukan utama untuk
masing-masing domain. `README.md` hanya README bawaan Laravel (tanpa info proyek).

**Utang dokumentasi yang disarankan:** perbarui atau hapus `DOKUMENTASI_DATABASE_DAN_CONTROLLER.md` (ganti dengan
ERD & data dictionary aktual), lalu buat README proyek yang menunjuk ke dokumen ini.

---

## 9. Ringkasan satu paragraf per dokumen asli

- **README.md** — README template Laravel: fitur framework, link belajar, Laravel Boost, kontribusi, lisensi.
  Tidak memuat informasi proyek laundry.
- **docs/DOKUMENTASI_DATABASE_DAN_CONTROLLER.md** — (outdated) ERD 8 tabel, kamus data, matriks route/controller,
  fungsionalitas modul, dan sequence diagram order lifecycle. Berguna sebagai referensi konsep, tetapi skema &
  route-nya sudah tidak cocok dengan kode.
- **docs/KEUANGAN.md** — aturan transaksi & keuangan: saluran outlet/online, ledger append-only dan jenis entri,
  permission per role, alur kasir/void/shift/pengeluaran/pembatalan, tutup buku, backfill & verify, laporan/export
  Excel, audit log, dan variabel `.env`.
- **docs/PEMBAYARAN.md** — integrasi Midtrans Snap: flag `PAYMENT_ENABLED`, diagram alur, daftar status
  checkout/pesanan/tagihan, arti `review_flag` (perlu refund), `.env`, setting dashboard sandbox, expose webhook
  (ngrok), scheduler & queue, rollback migration bertahap, dan checklist production.
