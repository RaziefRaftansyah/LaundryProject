@php
    $storeName = config('laundry.store_name', 'Laundry Coin');
    $city      = config('laundry.city', 'Balikpapan');
    $mapsEmbed = config('laundry.maps_embed_url');
    $mapsUrl   = config('laundry.google_maps_url', 'https://maps.google.com');
@endphp

<section id="hero" class="relative bg-primary-700 text-white">
    <div class="grid lg:grid-cols-2">

        {{-- Kolom kiri: klaim utama dan aksi --}}
        <div class="flex flex-col justify-center px-4 py-14 sm:px-8 sm:py-16 lg:px-12 lg:py-20 xl:px-16">
            <p class="flex items-center gap-2.5 text-sm font-semibold text-primary-100">
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-amber-300" aria-hidden="true"></span>
                <span>{{ $storeName }}, laundromat di {{ $city }}</span>
            </p>

            <h1 class="mt-5 font-display text-4xl font-extrabold leading-[1.05] tracking-tight text-white sm:text-5xl xl:text-6xl">
                Cuci, kering, lipat.
                <span class="block text-amber-300">Beres tanpa jemur.</span>
            </h1>

            <p class="mt-5 max-w-xl text-base leading-relaxed text-primary-50 sm:text-lg">
                Mesin cuci dan pengering berkapasitas besar, deterjen otomatis, dan pembayaran cashless. Datang langsung ke outlet atau pesan drop-off dari rumah.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a
                    href="{{ $mapsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-bold text-primary-800 transition-colors hover:bg-primary-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary-700"
                >
                    <x-icon name="map-pin" class="h-4 w-4" />
                    <span>Buka rute ke outlet</span>
                </a>

                <a
                    href="#layanan"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-white/40 px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary-700"
                >
                    <span>Lihat tarif layanan</span>
                </a>
            </div>

            <p class="mt-6 text-sm text-primary-100">
                <a
                    href="{{ route('tracking') }}"
                    class="font-semibold text-white underline decoration-amber-300 decoration-2 underline-offset-4 hover:decoration-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary-700"
                >
                    Lacak pesanan
                </a>
                <span class="text-primary-200"> dengan kode dari struk kasir.</span>
            </p>
        </div>

        {{-- Kolom kanan: peta lokasi outlet --}}
        <div
            class="relative min-h-[320px] isolate border-t-4 border-amber-300 bg-slate-200 sm:min-h-[420px] lg:min-h-0 lg:border-l-4 lg:border-t-0"
            x-data="{ mapAktif: false }"
        >
            {{-- Tampil selama peta dimuat, atau jika iframe gagal dirender --}}
            <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                <p class="max-w-xs text-sm leading-relaxed text-slate-600">
                    Memuat peta lokasi outlet.
                    <a
                        href="{{ $mapsUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="font-semibold text-primary-700 underline decoration-primary-300 decoration-2 underline-offset-4 hover:decoration-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
                    >
                        Buka di Google Maps
                    </a>
                    jika peta tidak muncul.
                </p>
            </div>

            <iframe
                src="{{ $mapsEmbed }}"
                title="Peta lokasi {{ $storeName }} di {{ $city }}"
                width="600"
                height="450"
                class="absolute inset-0 z-10 h-full w-full border-0"
                :class="mapAktif ? '' : 'pointer-events-none'"
                allowfullscreen
                referrerpolicy="strict-origin-when-cross-origin"
            ></iframe>

            {{-- Kartu lokasi: mengambang di sudut peta agar atribusi dan kontrol Google tetap terlihat --}}
            <div class="pointer-events-none absolute right-3 top-3 z-20 sm:right-4 sm:top-4">
                <div class="pointer-events-auto rounded-xl border border-slate-200 bg-white p-3 shadow-lg sm:p-4">
                    <p class="text-[11px] font-semibold text-slate-500">Lokasi outlet</p>
                    <p class="mt-0.5 font-display text-sm font-bold text-slate-900 sm:text-base">{{ $storeName }}, {{ $city }}</p>

                    <button
                        type="button"
                        x-on:click="mapAktif = !mapAktif"
                        :aria-pressed="mapAktif.toString()"
                        class="mt-2 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-800 transition-colors hover:border-primary-300 hover:bg-primary-50 hover:text-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
                    >
                        <x-icon name="map-pin" class="h-4 w-4" />
                        <span x-text="mapAktif ? 'Kunci peta' : 'Geser peta'">Geser peta</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
