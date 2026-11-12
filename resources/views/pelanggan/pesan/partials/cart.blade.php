@php
    $highlight = session('cart_highlight', []);
    $readonly = $readonly ?? false;
    $prices = $cart['orderable']->mapWithKeys(fn ($item) => [$item->id => $item->subtotal()]);
@endphp

<section
    class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    aria-labelledby="keranjang-title"
    @unless ($readonly)
        x-data="cartSelection({
            prices: {{ Js::from($prices) }},
            fresh: {{ Js::from(array_map('strval', $highlight)) }},
        })"
    @endunless
>
    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 pb-4 pt-5">
        <h2 id="keranjang-title" class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-50 text-primary-700 ring-1 ring-primary-200">
                <x-icon name="cart" class="h-5 w-5" />
            </span>
            Keranjang
        </h2>
        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-slate-100 px-2 text-xs font-extrabold text-slate-700">{{ $cart['items']->count() }} layanan</span>
    </div>

    @error('qty')
        <div class="mx-5 mt-4"><x-alert type="error" :message="$message" :dismissible="false" /></div>
    @enderror

    @if ($cart['items']->isEmpty())
        <div class="px-5 py-10 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <x-icon name="cart" class="h-6 w-6" />
            </div>
            <p class="text-base font-bold text-slate-800">Keranjang masih kosong</p>
            <p class="mx-auto mt-1 max-w-[42ch] text-sm leading-relaxed text-slate-700">Centang layanan di halaman Pesan, lalu klik ikon keranjang di bar bawah.</p>
            <a href="{{ route('pelanggan.pesan.index') }}" class="mt-4 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-primary-700 px-4 text-sm font-bold text-white transition-colors hover:bg-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-[0.99]">
                Pilih Layanan
            </a>
        </div>
    @else
        @if (! $readonly && $prices->isNotEmpty())
            <label class="flex min-h-11 cursor-pointer items-center gap-3 border-b border-slate-100 px-5 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-50">
                <input type="checkbox" :checked="allSelected()" x-on:change="toggleAll($event.target.checked)" class="h-5 w-5 rounded border-slate-300 text-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" />
                Pilih semua
            </label>
        @endif

        <ul class="divide-y divide-slate-100">
            @foreach ($cart['items'] as $item)
                @php $isNew = in_array($item->id, $highlight, true); $orderable = $item->isOrderable(); @endphp
                <li @class(['px-5 py-4', 'bg-emerald-50/70' => $isNew, 'opacity-60' => ! $orderable])>
                    <div class="flex items-start justify-between gap-3">
                        @if (! $readonly && $orderable)
                            <input
                                type="checkbox"
                                name="cart_items[]"
                                value="{{ $item->id }}"
                                form="lanjut-bayar"
                                x-model="selected"
                                class="mt-0.5 h-5 w-5 shrink-0 rounded border-slate-300 text-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                aria-label="Pilih {{ $item->service?->name }} untuk dibayar"
                            />
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold leading-snug text-slate-900">
                                {{ $item->service?->name ?? 'Layanan dihapus' }}
                                @if ($isNew)
                                    <span class="ml-1 align-middle rounded bg-emerald-700 px-1.5 py-0.5 text-[10px] font-extrabold text-white">BARU</span>
                                @endif
                            </p>
                            <p class="mt-0.5 text-xs text-slate-700">{{ $item->itemTypeLabel() }} · Rp {{ number_format($item->service?->price ?? 0, 0, ',', '.') }}/mesin</p>
                            @if ($item->notes)
                                <p class="mt-1 text-xs italic leading-relaxed text-slate-700">"{{ $item->notes }}"</p>
                            @endif
                            @unless ($orderable)
                                <p class="mt-1 text-xs font-bold text-rose-600">Layanan sedang tidak tersedia, tidak ikut dipesan. Hapus dari keranjang.</p>
                            @endunless
                        </div>
                        <p class="shrink-0 text-sm font-extrabold text-slate-900">Rp {{ number_format($item->subtotal(), 0, ',', '.') }}</p>
                    </div>

                    <div class="mt-3 flex items-center justify-between gap-3">
                        @if ($readonly || ! $orderable)
                            <span class="text-sm font-semibold text-slate-700">{{ $item->qty }} × mesin</span>
                        @else
                            {{-- Tombol -/+ langsung mengirim jumlah baru, tanpa perlu JavaScript --}}
                            <form action="{{ route('pelanggan.cart.update', $item->id) }}" method="POST" class="flex items-center gap-1.5">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="qty" value="{{ $item->qty - 1 }}" @disabled($item->qty <= 1) class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 transition-colors hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-95 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Kurangi">
                                    <x-icon name="minus" class="h-3.5 w-3.5" />
                                </button>
                                <span class="w-8 text-center text-sm font-extrabold text-slate-900">{{ $item->qty }}</span>
                                <button type="submit" name="qty" value="{{ $item->qty + 1 }}" @disabled($item->qty >= $maxQty) class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 transition-colors hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-95 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Tambah">
                                    <x-icon name="plus" class="h-3.5 w-3.5" />
                                </button>
                                <span class="ml-1 text-xs text-slate-700">mesin</span>
                            </form>
                        @endif

                        @unless ($readonly)
                            <form action="{{ route('pelanggan.cart.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex min-h-11 items-center gap-1.5 rounded-lg px-2 text-xs font-bold text-rose-600 transition-colors hover:bg-rose-50 hover:text-rose-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2">
                                    <x-icon name="trash" class="h-3.5 w-3.5" /> Hapus
                                </button>
                            </form>
                        @endunless
                    </div>
                </li>
            @endforeach
        </ul>

        @if ($readonly)
            <div class="space-y-3 rounded-b-2xl border-t border-slate-200 bg-slate-50/70 px-5 py-4">
                <div class="flex items-center justify-between gap-3 text-sm text-slate-700">
                    <span>Total mesin</span>
                    <span class="font-bold text-slate-900">{{ $cart['machine_count'] }} mesin</span>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Total</span>
                    <span class="text-xl font-extrabold text-primary-700">Rp {{ number_format($cart['total'], 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('pelanggan.cart.index') }}" class="inline-flex min-h-11 items-center text-sm font-bold text-primary-700 transition-colors hover:text-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                    Ubah isi keranjang
                </a>
            </div>
        @elseif ($prices->isEmpty())
            <p class="border-t border-slate-200 px-5 py-4 text-center text-xs font-semibold text-rose-600">Tidak ada layanan yang bisa dipesan di keranjang.</p>
        @else
            {{-- Checkbox item terhubung ke form ini lewat atribut form="lanjut-bayar" --}}
            <form
                id="lanjut-bayar"
                action="{{ route('pelanggan.pesan.index') }}"
                method="GET"
                class="sticky bottom-[4.25rem] z-20 rounded-b-2xl border-t border-slate-200 bg-white/95 px-5 py-4 shadow-[0_-8px_24px_-16px_rgba(15,23,42,0.35)] backdrop-blur md:bottom-0"
            >
                <input type="hidden" name="langkah" value="detail" />

                <p x-show="selected.length === 0" class="text-center text-sm font-semibold text-slate-700">Centang layanan yang ingin dibayar sekarang.</p>

                <div x-show="selected.length > 0" x-cloak class="flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm" aria-live="polite">
                        <p class="font-bold text-slate-900">Ingin melanjutkan pembayaran?</p>
                        <p class="text-slate-700"><span x-text="selected.length"></span> layanan · <span class="font-extrabold text-primary-700" x-text="rupiah(selectedTotal())"></span></p>
                    </div>
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-primary-700 px-5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-[0.99]">
                        Lanjutkan
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </button>
                </div>
            </form>
        @endif
    @endif
</section>

@unless ($readonly)
    @push('scripts')
    <script>
        // Pilihan hanya kenyamanan tampilan; item, harga, dan pemiliknya tetap dicek ulang di server.
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartSelection', ({ prices, fresh }) => ({
                prices,
                selected: [],
                init() {
                    let saved = [];
                    try { saved = JSON.parse(sessionStorage.getItem('lc_cart_selected')) ?? []; } catch (e) {}
                    // Ubah jumlah mesin memuat ulang halaman: pilihan dipertahankan, layanan yang baru masuk ikut tercentang
                    this.selected = [...new Set([...saved, ...fresh])].filter((id) => id in this.prices);
                    this.$watch('selected', (value) => {
                        try { sessionStorage.setItem('lc_cart_selected', JSON.stringify(value)); } catch (e) {}
                    });
                },
                allSelected() {
                    return this.selected.length === Object.keys(this.prices).length;
                },
                toggleAll(checked) {
                    this.selected = checked ? Object.keys(this.prices) : [];
                },
                selectedTotal() {
                    return this.selected.reduce((total, id) => total + (this.prices[id] ?? 0), 0);
                },
                rupiah(n) {
                    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
                },
            }));
        });
    </script>
    @endpush
@endunless
