<section id="lacak" class="border-b border-slate-200 bg-slate-50 py-14 lg:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-center">

            <div class="lg:col-span-5">
                <p class="flex items-center gap-2 text-sm font-semibold text-primary-700">
                    <span class="h-px w-8 bg-primary-700" aria-hidden="true"></span>
                    Lacak pesanan
                </p>
                <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Tahu posisi cucian tanpa perlu menelepon
                </h2>
                <p class="mt-3 max-w-md text-sm leading-relaxed text-slate-700 sm:text-base">
                    Setiap pesanan punya kode unik. Masukkan kode itu untuk melihat apakah cucian masih di antrean, sedang diproses, atau sudah siap diambil.
                </p>
            </div>

            <div class="lg:col-span-7">
                <form
                    method="GET"
                    action="{{ route('tracking') }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs sm:p-6"
                >
                    <label for="home-order-code" class="block text-sm font-semibold text-slate-900">
                        Kode pesanan
                    </label>
                    <p id="home-order-code-hint" class="mt-1 text-xs leading-relaxed text-slate-700">
                        Tertera di struk kasir atau pesan konfirmasi, contohnya LC-8F3K2Q.
                    </p>

                    <div class="mt-3 flex flex-col gap-3 sm:flex-row">
                        <input
                            id="home-order-code"
                            type="text"
                            name="order_code"
                            placeholder="LC-XXXXXX"
                            required
                            autocomplete="off"
                            aria-describedby="home-order-code-hint"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 font-mono text-sm uppercase text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />

                        <x-button type="submit" variant="primary" size="lg" class="min-h-11 shrink-0">
                            <x-icon name="search" class="h-4 w-4" />
                            <span>Lacak pesanan</span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
