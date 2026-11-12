@php
    $storeName = config('laundry.store_name', 'Laundry Coin');
    $city      = config('laundry.city', 'Balikpapan');
    $mapsUrl   = config('laundry.google_maps_url', 'https://maps.google.com');

    $whatsapp    = preg_replace('/[^0-9]/', '', (string) config('laundry.whatsapp', ''));
    $hasWhatsapp = strlen($whatsapp) >= 9;
@endphp

<section id="kontak" class="bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">

            <div class="lg:col-span-5">
                <p class="flex items-center gap-2 text-sm font-semibold text-primary-700">
                    <span class="h-px w-8 bg-primary-700" aria-hidden="true"></span>
                    Hubungi outlet
                </p>
                <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Tanya staf {{ $storeName }} langsung
                </h2>
                <p class="mt-3 max-w-md text-sm leading-relaxed text-slate-700 sm:text-base">
                    Ketersediaan mesin, tarif drop-off, atau cara pesan online bisa ditanyakan langsung ke staf outlet di {{ $city }}.@if ($hasWhatsapp) Balasan tercepat lewat WhatsApp.@endif
                </p>
            </div>

            <div class="space-y-3 lg:col-span-7">

                @if ($hasWhatsapp)
                    <a
                        href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Halo ' . $storeName . ', saya mau tanya layanan laundry') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex min-h-11 items-start gap-4 rounded-xl border border-slate-200 p-4 transition-colors hover:border-emerald-300 hover:bg-emerald-50/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 sm:p-5"
                    >
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-700 text-white" aria-hidden="true">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-base font-bold text-slate-900">WhatsApp outlet</span>
                            <span class="mt-1 block text-sm leading-relaxed text-slate-700">
                                Kirim pesan berisi pertanyaan atau kebutuhan cucian Anda, lalu tunggu balasan staf.
                            </span>
                        </span>
                    </a>
                @else
                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 sm:p-5">
                        <p class="text-sm font-bold text-slate-900">Nomor WhatsApp outlet belum tersedia</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-700">
                            Sementara ini, gunakan rute ke outlet atau halaman lacak pesanan di bawah.
                        </p>
                    </div>
                @endif

                <a
                    href="{{ $mapsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex min-h-11 items-start gap-4 rounded-xl border border-slate-200 p-4 transition-colors hover:border-primary-300 hover:bg-primary-50/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 sm:p-5"
                >
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-700 text-white" aria-hidden="true">
                        <x-icon name="map-pin" class="h-5 w-5" />
                    </span>
                    <span class="min-w-0">
                        <span class="block text-base font-bold text-slate-900">Rute ke outlet {{ $city }}</span>
                        <span class="mt-1 block text-sm leading-relaxed text-slate-700">
                            Buka titik outlet di Google Maps untuk petunjuk arah dari lokasi Anda.
                        </span>
                    </span>
                </a>

                <a
                    href="{{ route('tracking') }}"
                    class="flex min-h-11 items-start gap-4 rounded-xl border border-slate-200 p-4 transition-colors hover:border-primary-300 hover:bg-primary-50/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 sm:p-5"
                >
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-white" aria-hidden="true">
                        <x-icon name="package" class="h-5 w-5" />
                    </span>
                    <span class="min-w-0">
                        <span class="block text-base font-bold text-slate-900">Lacak pesanan</span>
                        <span class="mt-1 block text-sm leading-relaxed text-slate-700">
                            Masukkan kode pesanan dari struk kasir untuk melihat progres cucian.
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
