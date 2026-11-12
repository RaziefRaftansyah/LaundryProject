@php
    $stepLabels = ['cuci' => 'Cuci', 'kering' => 'Kering', 'lipat' => 'Lipat'];

    $serviceData = $services->map(fn ($s) => [
        'id'          => $s->id,
        'name'        => $s->name,
        'price'       => (float) $s->price,
        'unit'        => $s->unit ?? 'Mesin',
    ])->values();

    // Input form dipertahankan saat validasi gagal; kartu dari tombol "Pesan Sekarang" langsung tercentang.
    // Key items[] = id layanan, sehingga error "items.{id}.qty" otomatis tampil di kartu yang benar.
    $oldItems = old('items', []);
    $initial = $services->mapWithKeys(function ($s) use ($oldItems, $preselectedId) {
        $old = $oldItems[$s->id] ?? null;

        return [$s->id => [
            'checked'   => $old !== null || (empty($oldItems) && $preselectedId === $s->id),
            'qty'       => max(1, (int) ($old['qty'] ?? 1)),
            'item_type' => $old['item_type'] ?? 'pakaian',
            'notes'     => $old['notes'] ?? '',
            'weight'    => '',
        ]];
    });
@endphp

<form
    action="{{ route('pelanggan.cart.store') }}"
    method="POST"
    x-data="orderForm({
        services: {{ Js::from($serviceData) }},
        itemTypes: {{ Js::from($itemTypes) }},
        maxQty: {{ (int) $maxQty }},
        sel: {{ Js::from($initial) }},
        cartCount: {{ $cart['items']->count() }},
        cartUrl: {{ Js::from(route('pelanggan.cart.index')) }},
    })"
    x-on:submit="onSubmit($event)"
    class="rounded-2xl border border-slate-200 bg-white shadow-sm"
>
    @csrf
    <input type="hidden" name="lanjut" :value="proceed ? 1 : 0" />

    <div class="border-b border-slate-100 px-5 pb-4 pt-5 sm:px-6">
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex h-7 items-center rounded-full bg-primary-50 px-2.5 text-xs font-extrabold text-primary-700 ring-1 ring-primary-200">
                Langkah 1 dari 3
            </span>
            <span class="text-xs font-semibold text-slate-700">Pilih satu atau beberapa layanan</span>
        </div>
        <h2 class="mt-2 text-lg font-extrabold tracking-tight text-slate-900 sm:text-xl">1. Pilih Layanan Drop-Off</h2>
        <p class="mt-1 max-w-[70ch] text-sm leading-relaxed text-slate-700">
            Centang layanan yang Anda butuhkan. Ikon keranjang menyimpan pilihan agar bisa dilanjutkan nanti;
            tombol <strong class="font-bold text-slate-800">Lanjutkan</strong> menyimpan pilihan lalu membuka keranjang.
        </p>
    </div>

    @error('items')
        <div class="mx-5 mt-4 sm:mx-6"><x-alert type="error" :message="$message" :dismissible="false" /></div>
    @enderror

    @if ($services->isEmpty())
        <div class="p-6">
            <x-empty-state title="Belum Ada Layanan" description="Saat ini belum ada layanan drop-off yang dapat dipesan online." />
        </div>
    @else
        <div class="space-y-3 p-4 sm:p-6">
            @foreach ($services as $service)
                @php
                    $id = $service->id;
                    $cardErrors = collect(['service_id', 'qty', 'item_type', 'notes'])
                        ->map(fn ($f) => $errors->first("items.{$id}.{$f}"))
                        ->filter();
                @endphp
                <div
                    :class="sel[{{ $id }}].checked
                        ? 'border-primary-500 bg-primary-50/40 ring-4 ring-primary-100'
                        : 'border-slate-200 bg-white hover:border-primary-300 hover:shadow-sm'"
                    @class([
                        'rounded-2xl border-2 transition-all duration-150',
                        'outline-2 outline-offset-2 outline-rose-400' => $cardErrors->isNotEmpty(),
                    ])
                >
                    <!-- Header kartu: centang layanan -->
                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl p-4 focus-within:ring-2 focus-within:ring-primary-500 focus-within:ring-offset-2 sm:p-5">
                        <input
                            type="checkbox"
                            x-model="sel[{{ $id }}].checked"
                            class="mt-0.5 h-5 w-5 shrink-0 rounded border-slate-300 text-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                            aria-describedby="srv-{{ $id }}-desc"
                        />
                        <span class="min-w-0 flex-1">
                            <span class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1.5">
                                <span class="text-base font-extrabold text-slate-900">{{ $service->name }}</span>
                                <span class="inline-flex items-baseline gap-1 rounded-xl bg-white px-3 py-1.5 text-base font-extrabold text-primary-700 ring-1 ring-slate-200">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                    <span class="text-xs font-semibold text-slate-700">/ {{ $service->unit ?? 'Mesin' }}</span>
                                </span>
                            </span>
                            <span id="srv-{{ $id }}-desc" class="mt-1 block max-w-[70ch] text-sm leading-relaxed text-slate-700">
                                {{ $service->description }}
                            </span>
                            <span class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                                <span class="inline-flex min-h-7 items-center gap-1.5 rounded-full bg-slate-100 px-2.5 font-semibold text-slate-700">
                                    <x-icon name="clock" class="h-3.5 w-3.5" /> {{ $service->estimate }}
                                </span>
                                <span class="inline-flex min-h-7 items-center gap-1.5 rounded-full bg-slate-100 px-2.5 font-semibold text-slate-700">
                                    Proses:
                                    @foreach ($service->processSteps() as $proc)
                                        <span class="font-bold text-slate-900">{{ $stepLabels[$proc] ?? ucfirst($proc) }}</span>@if (! $loop->last)<span class="text-slate-400">→</span>@endif
                                    @endforeach
                                </span>
                            </span>
                        </span>
                    </label>

                    <!-- Detail layanan (hanya terkirim jika dicentang: input disabled tidak ikut dikirim) -->
                    <div x-show="sel[{{ $id }}].checked" x-collapse @if (! $initial[$id]['checked']) x-cloak @endif>
                        <div class="mx-4 mb-4 mt-1 grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:mx-5 sm:mb-5 sm:grid-cols-2">
                            <input type="hidden" name="items[{{ $id }}][service_id]" value="{{ $id }}" :disabled="! included({{ $id }})" />

                            <!-- Jenis cucian -->
                            <div>
                                <label for="srv-{{ $id }}-type" class="mb-1.5 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Jenis Cucian
                                    <x-help-tip text="Menentukan batas muatan per mesin: pakaian 6 KG, sprei/selimut tipis 3 KG, bedcover 1 pcs. Campuran memakai batas aman 3 KG." />
                                </label>
                                <select
                                    id="srv-{{ $id }}-type"
                                    name="items[{{ $id }}][item_type]"
                                    x-model="sel[{{ $id }}].item_type"
                                    :disabled="! included({{ $id }})"
                                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3.5 text-base text-slate-900 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                                >
                                    @foreach ($itemTypes as $key => $type)
                                        <option value="{{ $key }}">{{ $type['label'] }} (maks {{ $type['capacity'] }} {{ $type['unit'] }}/mesin)</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jumlah mesin -->
                            <div>
                                <label for="srv-{{ $id }}-qty" class="mb-1.5 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Jumlah Mesin
                                    <x-help-tip text="Satu mesin = satu kali proses. Belum yakin? Gunakan bantuan hitung (opsional) di bawah." />
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="button" x-on:click="dec({{ $id }})" :disabled="! sel[{{ $id }}].checked" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 transition-colors hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-95 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Kurangi jumlah mesin {{ $service->name }}">
                                        <x-icon name="minus" class="h-4 w-4" />
                                    </button>
                                    <input
                                        id="srv-{{ $id }}-qty"
                                        type="number"
                                        name="items[{{ $id }}][qty]"
                                        x-model.number="sel[{{ $id }}].qty"
                                        x-on:blur="clamp({{ $id }})"
                                        :disabled="! included({{ $id }})"
                                        min="1"
                                        max="{{ $maxQty }}"
                                        class="h-11 w-16 rounded-xl border border-slate-300 bg-white text-center text-base font-extrabold text-slate-900 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                                    />
                                    <button type="button" x-on:click="inc({{ $id }})" :disabled="! sel[{{ $id }}].checked" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 transition-colors hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-95 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Tambah jumlah mesin {{ $service->name }}">
                                        <x-icon name="plus" class="h-4 w-4" />
                                    </button>
                                    <span class="ml-auto truncate text-sm font-extrabold text-slate-900" x-text="rupiah(price({{ $id }}) * (sel[{{ $id }}].qty || 0))"></span>
                                </div>
                            </div>

                            <!-- Pembantu kapasitas (OPSIONAL): tidak dikirim ke server, hanya membantu memilih jumlah mesin -->
                            <div class="sm:col-span-2" x-data="{ helper: false }">
                                <button
                                    type="button"
                                    x-on:click="helper = ! helper"
                                    class="inline-flex min-h-11 items-center gap-1.5 rounded-lg text-sm font-bold text-amber-700 transition-colors hover:text-amber-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2"
                                    :aria-expanded="helper.toString()"
                                >
                                    <x-icon name="sparkles" class="h-4 w-4" />
                                    <span x-text="helper ? 'Tutup bantuan hitung mesin' : 'Bingung jumlah mesin? Hitung dari perkiraan muatan (opsional)'">Bingung jumlah mesin? Hitung dari perkiraan muatan (opsional)</span>
                                </button>
                                <div x-show="helper" x-collapse x-cloak class="mt-2 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-3">
                                    <div class="flex flex-wrap items-center gap-2 text-sm">
                                        <label for="srv-{{ $id }}-weight" class="font-bold text-amber-900">Perkiraan muatan <span class="font-medium">(opsional)</span></label>
                                        <input
                                            id="srv-{{ $id }}-weight"
                                            type="number"
                                            min="0"
                                            step="0.5"
                                            x-model="sel[{{ $id }}].weight"
                                            placeholder="0"
                                            class="h-11 w-24 rounded-lg border border-amber-300 bg-white px-3 text-base text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                        />
                                        <span class="text-amber-900" x-text="itemTypes[sel[{{ $id }}].item_type]?.unit ?? 'KG'"></span>
                                        <template x-if="suggestion({{ $id }})">
                                            <span class="flex flex-wrap items-center gap-2">
                                                <span class="text-amber-900">→ saran <strong x-text="suggestion({{ $id }})"></strong> mesin</span>
                                                <button type="button" x-on:click="sel[{{ $id }}].qty = suggestion({{ $id }})" class="min-h-11 rounded-lg bg-amber-500 px-3 text-sm font-bold text-slate-900 transition-colors hover:bg-amber-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2 active:scale-95">Pakai saran</button>
                                            </span>
                                        </template>
                                    </div>
                                    <p class="mt-1 text-xs leading-relaxed text-amber-800">Boleh dikosongkan. Hanya untuk membantu; petugas akan mengecek ulang muatan saat cucian diterima di outlet.</p>
                                </div>
                            </div>

                            <!-- Catatan per layanan -->
                            <div class="sm:col-span-2">
                                <label for="srv-{{ $id }}-notes" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan untuk layanan ini (opsional)</label>
                                <input
                                    id="srv-{{ $id }}-notes"
                                    type="text"
                                    name="items[{{ $id }}][notes]"
                                    x-model="sel[{{ $id }}].notes"
                                    :disabled="! included({{ $id }})"
                                    maxlength="500"
                                    placeholder="Contoh: pisahkan pakaian putih"
                                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3.5 text-base text-slate-900 placeholder:text-slate-700 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-100 disabled:cursor-not-allowed disabled:bg-slate-100"
                                />
                            </div>

                            @foreach ($cardErrors as $message)
                                <p class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-700 sm:col-span-2" role="alert">{{ $message }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bar bawah: ikon keranjang di kiri, tombol Lanjutkan di kanan. Muncul setelah ada layanan terpilih. -->
        <div
            x-show="selectedCount() > 0 || cartCount > 0"
            @if (! collect($initial)->contains('checked', true) && $cart['items']->isEmpty()) x-cloak @endif
            class="sticky bottom-[4.25rem] z-20 rounded-b-2xl border-t border-slate-200 bg-white/95 px-4 py-3 shadow-[0_-8px_24px_-16px_rgba(15,23,42,0.35)] backdrop-blur md:bottom-0 sm:px-6 sm:py-4"
        >
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-on:click="cartAction($el.form)"
                    :disabled="submitting"
                    :aria-label="selectedCount() > 0 ? 'Masukkan layanan terpilih ke keranjang' : 'Buka keranjang'"
                    :title="selectedCount() > 0 ? 'Masukkan layanan terpilih ke keranjang' : 'Buka keranjang'"
                    class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-primary-200 bg-primary-50 text-primary-700 transition-colors hover:bg-primary-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span x-show="submitting" x-cloak class="absolute inset-0 flex items-center justify-center rounded-xl bg-primary-50">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </span>
                    <x-icon name="cart" class="h-5 w-5" />
                    <span x-show="cartCount > 0" x-text="cartCount" class="absolute -right-1.5 -top-1.5 min-w-5 rounded-full bg-amber-400 px-1 text-center text-[10px] font-extrabold leading-5 text-slate-900" aria-hidden="true"></span>
                </button>

                <div class="min-w-0 flex-1 text-sm" aria-live="polite">
                    <p class="truncate font-bold text-slate-900" x-text="selectedCount() > 0 ? selectedCount() + ' layanan dipilih' : cartCount + ' layanan di keranjang'"></p>
                    <p class="truncate text-xs text-slate-700" x-show="selectedCount() > 0">Estimasi <span class="font-extrabold text-primary-700" x-text="rupiah(selectedTotal())"></span></p>
                </div>

                <button
                    type="button"
                    x-on:click="continueOrder($el.form)"
                    :disabled="submitting"
                    class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-primary-700 px-4 text-sm font-bold text-white shadow-sm transition-colors hover:bg-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60 sm:px-5"
                >
                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Lanjutkan'">Lanjutkan</span>
                    <span x-show="! submitting"><x-icon name="arrow-right" class="h-4 w-4" /></span>
                </button>
            </div>
        </div>
    @endif
</form>

@push('scripts')
<script>
    // Alpine hanya untuk kenyamanan & estimasi; harga, batas, dan ketersediaan tetap divalidasi server.
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', (config) => ({
            ...config,
            submitting: false,
            proceed: false,
            // Hanya layanan yang dicentang yang inputnya aktif (input disabled tidak ikut dikirim)
            included(id) {
                return this.sel[id].checked;
            },
            selectedIds() {
                return Object.keys(this.sel).map(Number).filter((id) => this.sel[id].checked);
            },
            selectedCount() {
                return this.selectedIds().length;
            },
            selectedTotal() {
                return this.selectedIds().reduce((total, id) => total + this.price(id) * (Number(this.sel[id].qty) || 0), 0);
            },
            price(id) {
                return this.services.find((s) => s.id === id)?.price ?? 0;
            },
            inc(id) {
                this.sel[id].qty = Math.min(this.maxQty, (Number(this.sel[id].qty) || 0) + 1);
            },
            dec(id) {
                this.sel[id].qty = Math.max(1, (Number(this.sel[id].qty) || 1) - 1);
            },
            clamp(id) {
                const qty = Math.round(Number(this.sel[id].qty) || 1);
                this.sel[id].qty = Math.min(this.maxQty, Math.max(1, qty));
            },
            suggestion(id) {
                const weight = parseFloat(this.sel[id].weight);
                const capacity = this.itemTypes[this.sel[id].item_type]?.capacity;
                if (! weight || weight <= 0 || ! capacity) return null;
                return Math.min(this.maxQty, Math.max(1, Math.ceil(weight / capacity)));
            },
            rupiah(n) {
                return 'Rp ' + Math.round(n).toLocaleString('id-ID');
            },
            save(form, proceed) {
                if (this.submitting) return;
                this.selectedIds().forEach((id) => this.clamp(id));
                this.proceed = proceed;
                this.submitting = true;
                // Tunggu nilai input diperbarui Alpine, baru kirim form
                this.$nextTick(() => form.submit());
            },
            cartAction(form) {
                if (this.selectedCount() > 0) this.save(form, false);
                else window.location.href = this.cartUrl;
            },
            continueOrder(form) {
                if (this.selectedCount() > 0) this.save(form, true);
                else window.location.href = this.cartUrl;
            },
            onSubmit(event) {
                // Tekan Enter di dalam input tidak mengirim form; simpan lewat tombol di bar bawah
                event.preventDefault();
            },
        }));
    });
</script>
@endpush
