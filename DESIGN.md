# DESIGN.md — Laundry Coin

## Identitas
- **Produk:** Laundry Coin — laundromat modern, self laundry langsung di outlet + layanan drop-off titip cuci, pembayaran cashless.
- **Audience:** pelanggan outlet di Balikpapan yang mencari cuci cepat, higienis, tanpa perlu jemur.
- **Visual language:** praktis, bersih, teratur, mesin-oriented (washer/coin motif). Tidak mencoba meniru Linear/Stripe/Vercel.

## Palet warna (2-3 core + 1 accent)
| Nama | Hex | Penggunaan | Alasan |
|------|-----|------------|--------|
| primary (merah) | #DC2626 | tombol utama, outline tombol selektif, accent navigasi | Brand utama, mudah dikenal, kontras tinggi di latar terang |
| info (biru) | #2563EB | kartu informasi, banner info, estimasi waktu | Dipisah dari brand agar tidak berubah saat brand diganti |
| coin (amber) | #F59E0B | badge kapasitas, ikon lokasi, highlight | Accent "uang/mesin" yang terpisah dari brand |
| slate (netral) | #0F172A..#F8FAFC | teks utama, latar halaman, border, surface | Netral, tidak ikut berubah saat brand diganti |
| success (emerald) | #10B981 | status siap diambil / lunas | Status positif |
| warning (amber) | #F59E0B | status pending | Peringatan halus |
| danger (rose/red) | #EF4444 | status batal / gagal | Status negatif |

**Alasan pemisahan:** warna info dan coin disengaja dipisah dari primary agar kartu/banner informasi tidak ikut berubah saat warna brand diganti (terdokumentasi di `resources/css/app.css`).

## Tipografi
| Elemen | Font | Alasan |
|--------|------|--------|
| Heading (h1-h6) | Plus Jakarta Sans | Membaca jelas, modern, cocok display judul |
| Body | DM Sans | Sans-serif bersih, mudah dibaca di layar kecil, cocok paragraf panjang |

**Alasan:** tidak ada alasan untuk font monospace besar atau uppercase berlebihan kecuali label form kecil yang konsisten di seluruh UI.

## Dial
- **ENERGY 1:** calm, praktis, bukan "wow". Laundry adalah layanan rutin, bukan produk percobaan.
- **RHYTHM 2:** konsisten dengan beberapa break (section hero berbeda dari layanan dan FAQ).
- **MOTION 1:** hover state, scroll-behavior smooth, transisi tombol. Tidak ada animasi elemen terlebih dahulu (no template fade-up/bounce).

## Konvensi komponen
- **Label form:** `uppercase tracking-wider text-xs font-semibold text-slate-700`. Konsisten di semua form (ditemukan di ~80 tempat). Alasan: label kecil dapat di-scan cepat, cocok form pemesanan.
- **Badge:** pil rounded-full dengan titik status (dot), konvensi status pesanan & pembayaran. Hanya digunakan untuk status nyata (pending, diproses, siap diambil, selesai, lunas, gagal). Tidak digunakan sebagai hiasan.
- **Border radius:** rounded-2xl kartu, rounded-lg tombol, rounded-full badge. Skala radius yang konsisten, tidak semua pill-shaped.
- **Ikon:** Lucide-style thin stroke. Dipilih karena relevansi konten (washer, truck, map-pin, clock, cart, search), bukan karena "terlihat AI".

## Layout & ritme
- Section publik menggunakan pola internal yang sama (eyebrow line + heading + paragraph + card grid) — **keputusan sengaja** (RHYTHM 2: sebagian konsisten, hero & FAQ berbeda komposisi).
- Tidak ada bento grid, tidak ada fake terminal, tidak ada "how it works" 3 langkah dengan ikon bundar default (cara-kerja 4 langkah benar-benar ada 4 langkah berlainan).
- Navbar hanya tautan ke section/halaman yang ada (R-24).

## Alasan utama (R-31)
- Warna brand merah: differensiasi laundry (bergabung dengan mesin) dan kontras tinggi di layar outlet.
- Pemisahan info vs coin agar perubahan brand tidak mengganggu informasi utilitas.
- Dial ENERGY 1 karena layanan rutin harus terasa dapat dipercaya, tidak mencoba mencuri perhatian.
- Label uppercase tracking-wider agar form pemesanan dapat di-scan cepat di mobile.
- **R-14 kartu sejenis identik:** kartu layanan dalam satu grup (self / non-self) dibuat seragam agar pelanggan bisa membandingkan tarif dengan cepat. Antar grup sudah dibedakan via warna ikon (primary-100 vs slate-900) dan badge kategori.
- **R-05 cara-kerja 4 langkah:** jumlah langkah mengikuti alur nyata (pilih, bayar, tunggu, selesai), bukan default 3 langkah. Konten setiap langkah berbeda.
- **R-05 ritme section seragam:** pilihan sengaja agar setiap section mudah di-scan. Hero, FAQ, dan contact sudah berbeda komposisi.
- **Stripe di header grup layanan:** garis vertikal warna primary-600 (self) vs slate-800 (non-self) sebagai penanda kategori, bukan hiasan.
- **R-29 3 core + status:** merah + biru + amber sebagai core; emerald/warning/danger dipakai khusus untuk status pesanan & pembayaran, bukan sebagai warna dekoratif.

## Data Outlet (config/laundry.php)
- Telepon: `0821-5030-5758` (config `phone`, fallback WA `6282150305758`).
- Jam operasional: Senin–Jumat `07.00 - 19.00`, Sabtu–Minggu `07.00 - 21.00` WITA (UTC+8). Last order 30 menit sebelum tutup; catatan operasional disertakan.
- Alamat/kota/peta diambil dari `config/laundry.php` dan ditampilkan di footer + section operasional/kontak.
- Nilai outlet dibagikan ke semua view via `View::share` di `App\Providers\AppServiceProvider::boot()`, jadi view tidak perlu membaca `config('laundry.*')` berulang-ulang.
- Deteksi placeholder `"[ISI ...]"` diposisikan di satu tempat: `App\Support\Placeholder::is()`.

## Warna diagram (Chart.js)
- Palet diagram dipisah dari warna UI dan diatur di satu tempat: `resources/css/app.css` (`--color-chart-*`).
- JS lewat `window.getChartColors()` (`resources/js/app.js`) membaca CSS variable saat runtime, bukan literal hex/rgba di setiap view.
- Digunakan di admin dashboard + panel laporan (trend, channel, metode).

## Deduplikasi kode
- `$isPlaceholder` closure: satu implementasi (`App\Support\Placeholder::is`) dipakai di footer + section operasional.
- Normalisasi digit WhatsApp: `App\Support\PhoneNumber::digits()` dipakai di section operasional + kontak.
