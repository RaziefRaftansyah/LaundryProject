<section id="hero" class="relative overflow-hidden bg-[#8F0F13] py-16 lg:py-24 border-b border-slate-200/60">
    <!-- Subtle Background Accents -->
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#FDECEC]/30 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -left-32 w-80 h-80 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <!-- Left Column: Copy & Actions -->
            <div class="lg:col-span-7 space-y-6 text-left">
                <!-- Headline -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#FAFAFA] tracking-tight leading-[1.15]">
                    Pakaian Bersih, Wangi & Kering Sempurna dalam <span class="text-[#FAFAFA] underline decoration-amber-400 decoration-wavy decoration-2">60 Menit</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-base sm:text-lg text-[#FDECEC] leading-relaxed max-w-2xl">
                    <strong>Laundry Coin</strong> menghadirkan self laundry praktis dengan mesin komersial berkapasitas besar, serta layanan drop-off yang bisa dipesan online. Tanpa ribet jemur, higienis dengan air filter steril, dan siap pakai seketika.
                </p>

                <!-- Action CTAs -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a
                        href="#layanan"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#FAFAFA] hover:bg-[#FDECEC] text-[#47080A] font-bold text-sm shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-[0.98]"
                    >
                        <span>Lihat Layanan & Tarif</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>

                    <a
                        href="https://wa.me/6282150305758?text=Halo%20Laundry%20Coin,%20saya%20ingin%20bertanya%20tentang%20layanan" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border border-[#FDECEC]/60 bg-[#8F0F13] hover:bg-[#83191C] text-[#FAFAFA] font-bold text-sm shadow-xs transition-all"
                    >
                        <svg class="w-4 h-4 text-[#22C55E]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.56 3.58.56a1 1 0 011 1V20a1 1 0 01-1 1C10.72 21 3 13.28 3 3a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.19 2.46.56 3.58a1 1 0 01-.24 1.01l-2.2 2.2z"/></svg>
                        <span>Kontak Kami</span>
                    </a>
                </div>

                <!-- Trust Points -->
                <div class="pt-6 border-t border-[#FDECEC]/40 grid grid-cols-3 gap-4 text-left">
                    <div>
                        <p class="text-xl sm:text-2xl font-extrabold text-[#FAFAFA]">12+ Unit</p>
                        <p class="text-xs text-[#FDECEC] mt-0.5">Mesin Siap Pakai</p>
                    </div>
                    <div>
                        <p class="text-xl sm:text-2xl font-extrabold text-[#FAFAFA]">45 Menit</p>
                        <p class="text-xs text-[#FDECEC] mt-0.5">Cuci & Kering Cepat</p>
                    </div>
                    <div>
                        <p class="text-sm sm:text-base font-extrabold text-[#FFA2A4]">Senin–Jumat {{ config('laundry.operating_hours.weekday', '07:00 - 19:00') }} WITA</p>
                        <p class="text-sm sm:text-base font-extrabold text-[#FFA2A4]">Sabtu–Minggu {{ config('laundry.operating_hours.weekend', '07:00 - 20:00') }} WITA</p>
                        <p class="text-xs text-[#FDECEC] mt-0.5">Jam Operasional</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Peta lokasi outlet -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="w-full max-w-md overflow-hidden rounded-3xl border border-slate-200 bg-[#8F0F13] shadow-xl">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.6942242315567!2d117.15719354581378!3d-0.4518988983706445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df679e11457daab%3A0x22987d217cc81316!2sLaundry%20Coin!5e0!3m2!1sid!2sid!4v1794206762160!5m2!1sid!2sid" title="Lokasi Laundry Coin di Google Maps" class="block h-80 w-full sm:h-[450px]" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>        </div>
    </div>
</section>
