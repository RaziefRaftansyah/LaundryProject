@php
    // Token unik per form: klik "Buat Pesanan" dua kali tetap menghasilkan 1 transaksi (dicek di server).
    $submissionToken = old('submission_token', (string) \Illuminate\Support\Str::uuid());
    $hasHours = collect($hours)->reject(fn ($v) => str_starts_with((string) $v, '[ISI'))->isNotEmpty();
@endphp

<form
    action="{{ route('pelanggan.checkout.store') }}"
    method="POST"
    x-data="{ submitting: false }"
    x-on:submit="if (submitting) { $event.preventDefault(); return; } submitting = true"
    class="rounded-2xl border border-slate-200 bg-white shadow-sm"
>
    @csrf
    <input type="hidden" name="submission_token" value="{{ $submissionToken }}" />
    @foreach ($cart['orderable'] as $item)
        <input type="hidden" name="cart_items[]" value="{{ $item->id }}" />
    @endforeach

    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 pb-4 pt-5 sm:px-6">
        <div class="min-w-0">
            <span class="inline-flex h-7 items-center rounded-full bg-primary-50 px-2.5 text-xs font-extrabold text-primary-700 ring-1 ring-primary-200">
                Langkah 2 dari 3
            </span>
            <h2 class="mt-2 text-lg font-extrabold tracking-tight text-slate-900 sm:text-xl">2. Detail & Konfirmasi Pesanan</h2>
            <p class="mt-1 max-w-[70ch] text-sm leading-relaxed text-slate-700">
                Tentukan kapan cucian diantar ke outlet. Layanan yang dipesan sudah tersedia di ringkasan.
            </p>
        </div>
        <a
            href="{{ route('pelanggan.cart.index') }}"
            class="inline-flex min-h-11 shrink-0 items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3.5 text-xs font-bold text-slate-700 transition-colors hover:border-primary-300 hover:bg-primary-50 hover:text-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
        >
            ← Kembali ke keranjang
        </a>
    </div>

    <div class="space-y-5 p-5 sm:p-6">
        @foreach (['cart', 'cart_items', 'submission_token'] as $key)
            @error($key)
                <x-alert type="error" :message="$message" :dismissible="false" />
            @enderror
        @endforeach

        <!-- Nama pemesan (dari akun) -->
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-700 text-xs font-extrabold text-white">{{ $user->initials() }}</span>
            <div class="min-w-0">
                <p class="text-xs text-slate-700">Dipesan atas nama</p>
                <p class="truncate text-sm font-bold text-slate-900">{{ $user->name }} <span class="font-normal text-slate-700">· {{ $user->email }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- No. HP: dari akun. Input hanya muncul jika akun belum punya nomor, lalu disimpan ke akun. -->
            <div>
                @if ($user->phone)
                    <p class="mb-1.5 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                        No. HP / WhatsApp
                        <x-help-tip text="Diambil dari akun Anda. Dipakai petugas untuk mengabari jika cucian sudah selesai atau ada kendala." />
                    </p>
                    <div class="flex min-h-11 items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm">
                        <span class="truncate font-bold text-slate-900">{{ $user->phone }}</span>
                        <a href="{{ route('profile.edit') }}" class="shrink-0 rounded-md text-xs font-bold text-primary-700 transition-colors hover:text-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">Ubah di profil</a>
                    </div>
                @else
                    <label for="contact_phone" class="mb-1.5 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                        No. HP / WhatsApp <span class="text-rose-500">*</span>
                        <x-help-tip text="Akun Anda belum punya nomor. Nomor ini disimpan ke akun dan dipakai petugas untuk mengabari Anda." />
                    </label>
                    <input
                        id="contact_phone"
                        type="tel"
                        name="contact_phone"
                        value="{{ old('contact_phone') }}"
                        required
                        inputmode="tel"
                        autocomplete="tel"
                        placeholder="08xxxxxxxxxx"
                        class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3.5 text-base text-slate-900 placeholder:text-slate-700 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-100 @error('contact_phone') border-rose-400 @enderror"
                    />
                    @error('contact_phone')
                        <p class="mt-1 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Rencana tanggal antar -->
            <div>
                <label for="drop_off_date" class="mb-1.5 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                    Rencana Antar ke Outlet <span class="text-rose-500">*</span>
                    <x-help-tip text="Tanggal Anda membawa cucian ke outlet. Pengerjaan dimulai setelah cucian diterima petugas." />
                </label>
                <input
                    id="drop_off_date"
                    type="date"
                    name="drop_off_date"
                    value="{{ old('drop_off_date', now()->toDateString()) }}"
                    min="{{ now()->toDateString() }}"
                    max="{{ $dropOffMax->toDateString() }}"
                    required
                    class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3.5 text-base text-slate-900 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-100 @error('drop_off_date') border-rose-400 @enderror"
                />
                @error('drop_off_date')
                    <p class="mt-1 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
                @else
                    <p class="mt-1 text-xs text-slate-700">Paling lambat {{ $dropOffMax->translatedFormat('d F Y') }}.</p>
                @enderror
            </div>
        </div>

        @if ($hasHours)
            <div class="flex gap-3 rounded-xl border border-info-200 bg-info-50 px-4 py-3 text-sm leading-relaxed text-info-900">
                <x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0" />
                <div>
                    <p class="font-bold">Jam operasional outlet</p>
                    <p>Senin-Jumat: {{ $hours['weekday'] ?? '-' }} · Sabtu-Minggu: {{ $hours['weekend'] ?? '-' }} {{ $hours['timezone'] ?? '' }}</p>
                </div>
            </div>
        @endif

        <!-- Catatan umum -->
        <div>
            <label for="notes" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan untuk Petugas (opsional)</label>
            <textarea
                id="notes"
                name="notes"
                rows="3"
                maxlength="1000"
                placeholder="Contoh: saya antar sore hari, mohon kabari via WhatsApp jika sudah selesai."
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-base leading-relaxed text-slate-900 placeholder:text-slate-700 focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-100"
            >{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Persetujuan aturan -->
        <div>
            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition-colors hover:border-primary-200 hover:bg-primary-50/50 focus-within:ring-2 focus-within:ring-primary-500 focus-within:ring-offset-2">
                <input type="checkbox" name="agree" value="1" @checked(old('agree')) required class="mt-0.5 h-5 w-5 shrink-0 rounded border-slate-300 text-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" />
                <span class="text-sm leading-relaxed text-slate-700">
                    Saya sudah membaca dan menyetujui <a href="{{ route('aturan') }}" target="_blank" rel="noopener" class="font-bold text-primary-700 underline underline-offset-2 hover:text-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">Aturan Penggunaan</a>,
                    termasuk batas muatan per mesin, dan akan mengantar cucian ke outlet sesuai tanggal di atas.
                </span>
            </label>
            @error('agree')
                <p class="mt-1 text-sm font-medium text-rose-600" role="alert">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="sticky bottom-[4.25rem] z-20 flex flex-wrap items-center justify-between gap-3 rounded-b-2xl border-t border-slate-200 bg-white/95 px-4 py-3 shadow-[0_-8px_24px_-16px_rgba(15,23,42,0.35)] backdrop-blur md:bottom-0 sm:px-6 sm:py-4 lg:static">
        <div>
            <p class="text-xs font-semibold text-slate-700">Total yang akan dibayar</p>
            <p class="text-lg font-extrabold text-primary-700 sm:text-xl">Rp {{ number_format($cart['total'], 0, ',', '.') }}</p>
        </div>
        <button
            type="submit"
            :disabled="submitting"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-primary-700 px-5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-primary-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60"
        >
            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span x-text="submitting ? 'Membuat pesanan...' : 'Buat Pesanan & Lanjut Bayar'">Buat Pesanan &amp; Lanjut Bayar</span>
            <span x-show="! submitting"><x-icon name="arrow-right" class="h-4 w-4" /></span>
        </button>
    </div>
</form>
