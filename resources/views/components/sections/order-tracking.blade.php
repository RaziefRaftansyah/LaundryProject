<section id="lacak" class="py-16 lg:py-20 bg-[#FAFAFA] border-b border-slate-200/60">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FDECEC] text-[#D91B20] text-xs font-bold uppercase tracking-wider mb-3">
            Realtime Status
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Lacak Pesanan Laundry Anda
        </h2>
        <p class="text-sm text-slate-600 mt-2 max-w-xl mx-auto">
            Masukkan kode pesanan Anda (contoh: <span class="font-mono font-semibold text-[#D91B20]">LC-XXXXXX</span>) untuk melihat status pengerjaan cucian secara langsung.
        </p>

        <form method="GET" action="{{ route('tracking') }}" class="mt-8 flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <x-icon name="search" class="w-5 h-5" />
                </span>
                <input
                    type="text"
                    name="order_code"
                    placeholder="Masukkan Kode Pesanan (LC-XXXXXX)"
                    required
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-[#FAFAFA] text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#FDECEC] focus:border-[#FDECEC] font-mono text-sm uppercase shadow-xs"
                />
            </div>
            <x-button type="submit" variant="primary" size="lg" class="rounded-xl shrink-0">
                <x-icon name="search" class="w-4 h-4" />
                <span>Lacak Pesanan</span>
            </x-button>
        </form>
    </div>
</section>
