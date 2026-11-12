@php
    $storeName = config('laundry.store_name', 'Laundry Coin');
    $city      = config('laundry.city', 'Balikpapan');
    $mapsUrl   = config('laundry.google_maps_url', 'https://maps.google.com');

    // Nilai "[ISI ...]" adalah placeholder bawaan config, bukan data asli.
    $isPlaceholder = fn ($value) => trim((string) $value) === '' || str_contains((string) $value, '[ISI');

    $address    = (string) config('laundry.address', '');
    $hoursDay   = (string) config('laundry.operating_hours.weekday', '');
    $hoursEnd   = (string) config('laundry.operating_hours.weekend', '');
    $lastOrder  = (string) config('laundry.operating_hours.last_order', '');
    $timezone   = (string) config('laundry.operating_hours.timezone', '');
    $notes      = (string) config('laundry.operating_hours.notes', '');

    $hoursKnown = ! $isPlaceholder($hoursDay) || ! $isPlaceholder($hoursEnd);
    $tzLabel    = $isPlaceholder($timezone) ? '' : ' ' . $timezone;

    $whatsapp    = preg_replace('/[^0-9]/', '', (string) config('laundry.whatsapp', ''));
    $hasWhatsapp = strlen($whatsapp) >= 9;

    $facilities = [
        'Ruang tunggu ber-AC',
        'WiFi gratis dan colokan charger',
        'Pembayaran cashless di kasir',
        'Meja lipat dan keranjang steril',
    ];
@endphp

<section id="operasional" class="border-b border-slate-200 bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7">
                <p class="flex items-center gap-2 text-sm font-semibold text-primary-700">
                    <span class="h-px w-8 bg-primary-700" aria-hidden="true"></span>
                    Jam buka dan fasilitas
                </p>
                <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Mampir saat cucian sudah menumpuk
                </h2>
            </div>
            <p class="text-sm leading-relaxed text-slate-600 sm:text-base lg:col-span-5">
                Outlet {{ $storeName }} di {{ $city }} melayani cuci mandiri dan drop-off. Parkir tersedia di depan outlet, dan ruang tunggu ber-AC membuat menunggu mesin selesai tetap nyaman.
            </p>
        </div>

        <div class="mt-12 grid gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 md:grid-cols-3">

            {{-- Jam buka --}}
            <div class="bg-white p-6 sm:p-7">
                <h3 class="text-sm font-bold text-slate-900">Jam buka</h3>

                @if ($hoursKnown)
                    <dl class="mt-4 space-y-2 text-sm">
                        @if (! $isPlaceholder($hoursDay))
                            <div class="flex items-baseline justify-between gap-3 border-b border-slate-100 pb-2">
                                <dt class="text-slate-600">Senin sampai Jumat</dt>
                                <dd class="font-semibold text-slate-900">{{ $hoursDay }}{{ $tzLabel }}</dd>
                            </div>
                        @endif
                        @if (! $isPlaceholder($hoursEnd))
                            <div class="flex items-baseline justify-between gap-3 border-b border-slate-100 pb-2">
                                <dt class="text-slate-600">Sabtu dan Minggu</dt>
                                <dd class="font-semibold text-slate-900">{{ $hoursEnd }}{{ $tzLabel }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if (! $isPlaceholder($lastOrder))
                        <p class="mt-4 text-xs leading-relaxed text-slate-500">
                            Pesanan self laundry terakhir {{ $lastOrder }}{{ $tzLabel }} agar cuci dan kering selesai sebelum outlet tutup.
                        </p>
                    @endif
                    @if (! $isPlaceholder($notes))
                        <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ $notes }}</p>
                    @endif
                @else
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                        Jam buka outlet belum dipublikasikan di halaman ini.
                    </p>

                    @if ($hasWhatsapp)
                        <a
                            href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Halo ' . $storeName . ', jam buka outlet hari ini sampai jam berapa?') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-4 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-primary-700 underline decoration-primary-300 decoration-2 underline-offset-4 hover:text-primary-800 hover:decoration-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
                        >
                            Tanyakan jam buka lewat WhatsApp
                        </a>
                    @endif
                @endif
            </div>

            {{-- Fasilitas --}}
            <div class="bg-white p-6 sm:p-7">
                <h3 class="text-sm font-bold text-slate-900">Fasilitas di outlet</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-600">
                    @foreach ($facilities as $facility)
                        <li class="flex items-start gap-2.5">
                            <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-primary-600" />
                            <span>{{ $facility }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Alamat & rute --}}
            <div class="bg-white p-6 sm:p-7">
                <h3 class="text-sm font-bold text-slate-900">Alamat dan rute</h3>

                @if (! $isPlaceholder($address))
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">{{ $address }}</p>
                @else
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                        Titik outlet {{ $storeName }} sudah terpasang di peta beranda. Alamat lengkap dan petunjuk jalan tersedia di Google Maps.
                    </p>
                @endif

                <a
                    href="{{ $mapsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-4 inline-flex min-h-11 items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 transition-colors hover:border-primary-300 hover:bg-primary-50 hover:text-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
                >
                    <x-icon name="map-pin" class="h-4 w-4 text-primary-600" />
                    <span>Buka rute di Google Maps</span>
                </a>
            </div>
        </div>
    </div>
</section>
