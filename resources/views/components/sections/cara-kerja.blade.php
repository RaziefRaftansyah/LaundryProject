@php
    $paymentMethods = config('laundry.online_payment_methods', []);
@endphp

<section id="cara-kerja" class="py-16 lg:py-24 bg-slate-50 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-bold uppercase tracking-wider border border-amber-200 mb-3">
                Simpel & Cashless
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                4 Langkah Mudah Mencuci di Laundry Coin
            </h2>
            <p class="text-base text-slate-600 mt-3 leading-relaxed">
                Ikuti empat langkah singkat dari memilih layanan hingga mengambil pakaian.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- Step 1: Pilih Layanan -->
            <div class="bg-[#FAFAFA] rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-[#D91B20] text-[#FAFAFA] font-extrabold text-base flex items-center justify-center mb-5 shadow-xs">
                    01
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-3">Pilih Layanan</h3>

                <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                    <div class="rounded-xl bg-[#FDECEC]/70 border border-[#FDECEC] p-3">
                        <p class="font-bold text-[#D91B20] mb-1">Pemesanan Online</p>
                        <p>Masuk ke akun Anda, pilih layanan Drop-Off di bagian Layanan lalu klik <strong>Pesan Sekarang</strong>. Tentukan jumlah paket, buat pesanan, lalu bawa pakaian Anda ke outlet dan serahkan ke petugas.</p>
                    </div>
                    <div class="rounded-xl bg-amber-50/70 border border-amber-100 p-3">
                        <p class="font-bold text-amber-800 mb-1">Langsung di Tempat (Self Laundry)</p>
                        <p>Datang ke outlet, pilih mesin siap pakai, lalu masukkan pakaian sesuai kapasitas.</p>
                    </div>
                </div>

                <div class="mt-auto pt-4">
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] font-semibold text-[#D91B20]">
                        <x-icon name="check" class="w-4 h-4 shrink-0" />
                        <span>Deterjen Otomatis Diberikan</span>
                    </div>
                </div>
            </div>

            <!-- Step 2: Bayar Cashless -->
            <div class="bg-[#FAFAFA] rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-900 font-extrabold text-base flex items-center justify-center mb-5 shadow-xs">
                    02
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-3">Bayar Cashless</h3>

                <ol class="space-y-1.5 text-xs text-slate-600 leading-relaxed list-decimal list-inside mb-4">
                    <li>Pilih metode pembayaran.</li>
                    <li>Bayar sesuai total tagihan.</li>
                    <li>Pembayaran diverifikasi otomatis.</li>
                    <li>Cek status <strong>Lunas</strong> di riwayat.</li>
                </ol>

                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Metode Pembayaran</p>
                <div class="space-y-2">
                    @foreach ($paymentMethods as $method)
                        <div class="rounded-xl border border-slate-200 p-3">
                            <p class="text-xs font-bold text-slate-900">{{ $method['label'] }}</p>
                            <p class="text-[11px] text-slate-500 leading-relaxed">{{ $method['description'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-auto pt-4">
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] font-semibold text-amber-700">
                        <x-icon name="check" class="w-4 h-4 shrink-0" />
                        <span>Self Laundry di outlet: bayar langsung di kasir</span>
                    </div>
                </div>
            </div>

            <!-- Step 3: Menunggu Estimasi Penyelesaian -->
            <div class="bg-[#FAFAFA] rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-[#D91B20] text-[#FAFAFA] font-extrabold text-base flex items-center justify-center mb-5 shadow-xs">
                    03
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-3">Tunggu Proses</h3>

                <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                    <p>Lihat estimasi selesai di detail pesanan.</p>
                    <p>Drop-Off diproses bertahap: cuci, kering, lalu lipat.</p>
                    <p>Self Laundry dapat ditunggu di lounge.</p>
                </div>

                <div class="mt-auto pt-4">
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] font-semibold text-[#D91B20]">
                        <x-icon name="check" class="w-4 h-4 shrink-0" />
                        <span>Free WiFi & AC Sejuk</span>
                    </div>
                </div>
            </div>

            <!-- Step 4: Pesanan Selesai -->
            <div class="bg-[#FAFAFA] rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-[#FAFAFA] font-extrabold text-base flex items-center justify-center mb-5 shadow-xs">
                    04
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-3">Ambil Pakaian</h3>

                <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                    <p>Saat status <strong>Selesai</strong>, ambil pakaian dengan kode pesanan.</p>
                    <p>Keringkan pakaian, lalu lipat di meja yang tersedia.</p>
                </div>

                <div class="mt-auto pt-4">
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-[11px] font-semibold text-[#22C55E]">
                        <x-icon name="check" class="w-4 h-4 shrink-0" />
                        <span>100% Kering Tanpa Jemur</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
