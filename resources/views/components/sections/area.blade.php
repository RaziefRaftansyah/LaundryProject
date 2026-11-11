<section id="operasional" class="py-16 lg:py-24 bg-[#FAFAFA] border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DBEAFE] text-[#1D4ED8] text-xs font-bold uppercase tracking-wider border border-[#93C5FD]">Keunggulan Laundry Coin</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">Lebih Praktis, Bersih, dan Terukur</h2>
            <p class="text-base text-slate-600 leading-relaxed mt-3">Nikmati pengalaman laundry yang dirancang agar cepat, nyaman, dan mudah dipantau.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach([
                ['title'=>'Free Wifi','text'=>'Tetap produktif dan nyaman selama menunggu proses selesai.','icon'=>'wifi'],
                ['title'=>'Cashless','text'=>'Pembayaran praktis dengan metode non-tunai.','icon'=>'banknote'],
                ['title'=>'Murah','text'=>'Harga transparan untuk layanan laundry yang efisien.','icon'=>'smile'],
                ['title'=>'Cepat','text'=>'Proses dirancang singkat dengan tahapan yang jelas.','icon'=>'clock'],
                ['title'=>'Pakaian Hilang Minim Terjadi','text'=>'Pencatatan dan proses terstruktur membantu menjaga pakaian tetap aman.','icon'=>'shield-check'],
            ] as $benefit)
                <div class="rounded-2xl border border-slate-200 bg-[#FAFAFA] p-6 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-[#DBEAFE] text-[#3B82F6] flex items-center justify-center mb-4"><x-icon name="{{ $benefit['icon'] }}" class="w-5 h-5" /></div>
                    <h3 class="font-bold text-slate-900">{{ $benefit['title'] }}</h3>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $benefit['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
