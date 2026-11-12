@extends('layouts.guest')

@section('title', 'Pesan Layanan Laundry - Laundry Coin')

@section('content')
<div class="mx-auto max-w-7xl px-4 pb-28 pt-4 sm:px-6 sm:pt-6 lg:px-8 lg:pb-16">
    {{-- Breadcrumb: memberi tahu posisi halaman & jalan kembali --}}
    <nav aria-label="Breadcrumb" class="text-sm">
        <ol class="flex flex-wrap items-center gap-2 text-slate-700">
            <li>
                <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center gap-1.5 rounded-lg font-semibold transition-colors hover:text-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Beranda
                </a>
            </li>
            <li aria-hidden="true" class="text-slate-300">/</li>
            <li>
                <span aria-current="page" class="font-semibold text-slate-700">Pesan Layanan</span>
            </li>
        </ol>
    </nav>

    {{-- Hero halaman: judul utama (H1) + nilai yang didapat pelanggan --}}
    <header class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary-700">
                <x-icon name="washer" class="h-4 w-4" />
                Drop-off online
            </span>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Pesan Layanan Laundry
            </h1>
            <p class="mt-3 max-w-[62ch] text-base leading-relaxed text-slate-700">
                Pilih layanan, atur jenis dan jumlah mesin, lalu lanjutkan ke pembayaran.
                Harga dan ketersediaan selalu dicek ulang oleh sistem agar pesanan Anda aman.
            </p>
        </div>

        <ul class="flex flex-wrap gap-2 text-sm" aria-label="Keunggulan pemesanan online">
            <li class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 font-semibold text-slate-700">
                <x-icon name="coin" class="h-4 w-4 text-primary-700" />
                Harga transparan
            </li>
            <li class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 font-semibold text-slate-700">
                <x-icon name="check" class="h-4 w-4 text-primary-700" />
                Bayar online
            </li>
            <li class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 font-semibold text-slate-700">
                <x-icon name="calendar" class="h-4 w-4 text-primary-700" />
                Antar maks {{ (int) config('laundry.drop_off_max_days', 7) }} hari
            </li>
        </ul>
    </header>

    {{-- Progres pemesanan: pengguna selalu tahu ada di langkah mana --}}
    <div class="mt-6">
        <x-order-stepper :current="$step" />
    </div>

    {{-- Panduan singkat yang bisa dilipat --}}
    <div class="mt-4">
        @include('pelanggan.pesan.partials.tutorial')
    </div>

    <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_22rem] xl:grid-cols-[minmax(0,1fr)_24rem]">
        <div class="min-w-0">
            @if ($step === 'detail')
                @include('pelanggan.pesan.partials.detail-form')
            @else
                <div id="pilih-layanan" class="scroll-mt-24">
                    @include('pelanggan.pesan.partials.service-form')
                </div>
            @endif
        </div>

        @if ($step === 'detail')
            <aside class="min-w-0 lg:sticky lg:top-24" aria-label="Ringkasan pesanan">
                @include('pelanggan.pesan.partials.cart', ['readonly' => true])
            </aside>
        @else
            <aside class="hidden space-y-4 lg:sticky lg:top-24 lg:block" aria-label="Ringkasan keranjang">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Keranjang Anda</h2>
                        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-slate-100 px-2 text-xs font-extrabold text-slate-700">
                            {{ $cart['items']->count() }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700">
                        @if ($cart['items']->isEmpty())
                            Belum ada layanan tersimpan. Centang layanan di samping untuk mulai memesan.
                        @else
                            {{ $cart['machine_count'] }} mesin siap diproses. Pilih yang ingin dibayar di halaman keranjang.
                        @endif
                    </p>

                    <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-700">Total sementara</dt>
                            <dd class="font-extrabold text-slate-900">Rp {{ number_format($cart['total'], 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <a
                        href="{{ route('pelanggan.cart.index') }}"
                        class="mt-4 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-bold text-slate-800 transition-colors hover:border-primary-300 hover:bg-primary-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 active:scale-[0.99]"
                    >
                        <x-icon name="cart" class="h-4 w-4" />
                        Buka keranjang
                    </a>
                </section>

                <section class="rounded-2xl border border-info-200 bg-info-700 p-5 text-white shadow-sm">
                    <h2 class="text-lg font-extrabold tracking-tight">Butuh bantuan?</h2>
                    <p class="mt-2 max-w-[38ch] text-sm leading-relaxed text-info-100">
                        Tim outlet siap membantu memilih layanan atau jumlah mesin yang tepat untuk cucian Anda.
                    </p>
                    <a
                        href="{{ route('home') }}#kontak"
                        class="mt-4 inline-flex min-h-11 items-center gap-2 rounded-xl bg-white/10 px-4 text-sm font-bold text-white transition-colors hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-info-700"
                    >
                        <x-icon name="phone" class="h-4 w-4" />
                        Hubungi outlet
                    </a>
                </section>
            </aside>
        @endif
    </div>
</div>
@endsection
