@php
    $tutorialSteps = [
        ['icon' => 'washer', 'title' => 'Centang layanan', 'text' => 'Centang satu atau beberapa layanan drop-off. Kartu yang dicentang akan terbuka.'],
        ['icon' => 'package', 'title' => 'Atur jenis & jumlah', 'text' => 'Pilih jenis cucian dan jumlah mesin (bantuan hitung bersifat opsional), lalu klik ikon keranjang di bar bawah atau tombol Lanjutkan.'],
        ['icon' => 'cart', 'title' => 'Pilih yang dibayar', 'text' => 'Di halaman keranjang, centang layanan yang ingin dibayar sekarang. Sisanya tetap tersimpan. Lanjutkan untuk mengisi tanggal antar.'],
        ['icon' => 'coin', 'title' => 'Bayar & antar', 'text' => 'Bayar online (Transfer/QRIS), lalu antar cucian ke outlet sesuai tanggal dan tunjukkan kode pesanan.'],
    ];
@endphp

{{-- Terbuka otomatis pada kunjungan pertama; status buka/tutup diingat di browser ini. --}}
<section
    x-data="{
        open: true,
        init() {
            try { this.open = localStorage.getItem('lc_tutorial_pesan') !== 'tutup'; } catch (e) {}
        },
        toggle() {
            this.open = ! this.open;
            try { localStorage.setItem('lc_tutorial_pesan', this.open ? 'buka' : 'tutup'); } catch (e) {}
        },
    }"
    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    aria-labelledby="tutorial-pesan-title"
>
    <button
        type="button"
        x-on:click="toggle()"
        class="flex min-h-16 w-full items-center justify-between gap-3 px-4 py-3.5 text-left transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-info-500 sm:px-5"
        :aria-expanded="open.toString()"
        aria-controls="tutorial-pesan-body"
    >
        <span class="flex min-w-0 items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-info-200 bg-info-50 text-info-700">
                <x-icon name="info" class="h-5 w-5" />
            </span>
            <span class="min-w-0">
                <span class="block text-xs font-bold uppercase tracking-wider text-info-700">Panduan pemesanan</span>
                <span id="tutorial-pesan-title" class="block text-base font-extrabold leading-tight text-slate-900">Cara Memesan di Halaman Ini</span>
                <span class="mt-0.5 hidden text-sm text-slate-500 sm:block">4 langkah singkat, tidak perlu kembali ke halaman utama.</span>
            </span>
        </span>
        <span class="flex shrink-0 items-center gap-1.5 rounded-lg px-2 py-1 text-xs font-bold text-info-700">
            <span x-text="open ? 'Sembunyikan' : 'Lihat panduan'">Sembunyikan</span>
            <span class="transition-transform duration-200" :class="open && 'rotate-180'">
                <x-icon name="chevron-down" class="h-4 w-4" />
            </span>
        </span>
    </button>

    <div id="tutorial-pesan-body" x-show="open" x-collapse>
        <ol class="grid grid-cols-1 gap-3 border-t border-slate-100 p-4 sm:grid-cols-2 sm:p-5 lg:grid-cols-4">
            @foreach ($tutorialSteps as $i => $t)
                <li class="relative rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <span class="absolute right-3 top-3 text-[11px] font-extrabold text-slate-300">0{{ $i + 1 }}</span>
                    <span class="mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-white text-info-700 shadow-sm ring-1 ring-slate-200">
                        <x-icon :name="$t['icon']" class="h-4 w-4" />
                    </span>
                    <p class="text-sm font-extrabold text-slate-900">{{ $t['title'] }}</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $t['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
