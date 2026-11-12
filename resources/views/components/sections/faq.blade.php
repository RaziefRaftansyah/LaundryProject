@php
    $faqs = [
        [
            'q' => 'Bagaimana cara membayar pesanan di Laundry Coin?',
            'a' => 'Laundry Coin tidak menggunakan koin atau token. Untuk pesanan online, pembayaran dilakukan secara cashless melalui Transfer Bank atau E-Wallet / QRIS. Pilih metode saat membuat pesanan, bayar sesuai total tagihan, lalu petugas akan memverifikasi dan status pesanan Anda berubah menjadi Lunas. Untuk Self Laundry langsung di outlet, pembayaran dilakukan di kasir.'
        ],
        [
            'q' => 'Apakah deterjen dan pelembut pakaian disediakan gratis?',
            'a' => 'Ya, betul! Mesin cuci kami telah terhubung dengan sistem injeksi deterjen cair premium dan softener otomatis yang telah terukur secara presisi. Anda tidak perlu repot membawa atau menuangkan sabun sendiri.'
        ],
        [
            'q' => 'Berapa lama waktu yang dibutuhkan untuk mencuci dan mengeringkan pakaian?',
            'a' => 'Siklus cuci cepat berlangsung sekitar 35 menit, dan siklus pengeringan (dryer) berlangsung 40 menit. Jadi hanya dalam waktu kurang lebih 75 menit (1 jam 15 menit), pakaian Anda sudah bersih, steril bebas tungau, wangi, dan kering siap pakai.'
        ],
        [
            'q' => 'Apakah saya boleh meninggalkan cucian saat mesin sedang berputar?',
            'a' => 'Boleh sekali! Pintu mesin cuci terkunci otomatis demi keamanan selama siklus berjalan. Anda bisa menunggu di waiting lounge ber-AC kami atau bepergian ke toko sekitar. Namun mohon kembali tepat waktu saat timer selesai agar tidak menghambat pengguna lain.'
        ],
        [
            'q' => 'Apa perbedaan antara layanan Self Laundry dan Drop-Off?',
            'a' => 'Self Laundry adalah layanan cuci mandiri langsung di outlet (tidak bisa dipesan online) di mana Anda sendiri yang memasukkan pakaian dan memindahkan ke pengering dengan harga hemat per siklus. Sedangkan layanan Drop-Off adalah layanan terima beres, di mana staf kami yang mencuci, mengeringkan, dan melipat rapi sesuai paket yang dipilih. Layanan Drop-Off bisa dipesan online.'
        ],
        [
            'q' => 'Apakah Laundry Coin menyediakan layanan Antar-Jemput (Pickup & Delivery)?',
            'a' => 'Saat ini Laundry Coin belum menyediakan layanan antar-jemput. Anda dapat memesan layanan Drop-Off secara online, lalu membawa dan mengambil pakaian langsung di outlet. Status pesanan bisa dipantau melalui akun Anda atau menu Lacak Pesanan.'
        ],
    ];
@endphp

<section id="faq" class="py-16 lg:py-24 bg-slate-50 border-b border-slate-200/60">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 max-w-2xl">
            <p class="flex items-center gap-2 text-sm font-semibold text-primary-700">
                <span class="h-px w-8 bg-primary-700" aria-hidden="true"></span>
                Pertanyaan umum
            </p>
            <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Yang paling sering ditanyakan pelanggan
            </h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">
                Pembayaran, durasi mesin, fasilitas, dan cara pemesanan di Laundry Coin.
            </p>
        </div>

        <div x-data="{ active: null }" class="space-y-3.5">
            @foreach ($faqs as $index => $item)
                <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-xs transition-colors">
                    <button
                        type="button"
                        x-on:click="active = (active === {{ $index }} ? null : {{ $index }})"
                        :aria-expanded="(active === {{ $index }}).toString()"
                        aria-controls="faq-panel-{{ $index }}"
                        class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-semibold text-slate-900 hover:text-primary-600 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-2xl"
                    >
                        <span class="text-sm sm:text-base">{{ $item['q'] }}</span>
                        <div
                            class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200"
                            :class="active === {{ $index }} ? 'rotate-180 bg-primary-100 text-primary-700' : ''"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <div
                        id="faq-panel-{{ $index }}"
                        x-show="active === {{ $index }}"
                        x-collapse
                        class="border-t border-slate-100 px-6 py-4 bg-slate-50/50"
                        style="display: none;"
                    >
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ $item['a'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
