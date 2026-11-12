@props([
    'active' => null, // beranda | transaksi | pesan | keranjang | akun
])

@php
    $user = auth()->user();
    $cartCount = $user?->isPelanggan() ? $user->cartItems()->count() : 0;

    // Deteksi tab aktif otomatis bila halaman tidak mengirim prop $active.
    $active ??= match (true) {
        request()->routeIs('home', 'tentang', 'aturan') => 'beranda',
        request()->routeIs('pelanggan.orders.start') => 'pesan',
        request()->routeIs('pelanggan.pesan.*') => 'pesan',
        request()->routeIs('pelanggan.cart.*') => 'keranjang',
        request()->routeIs('pelanggan.orders.*', 'pelanggan.transaksi.*', 'pelanggan.pembayaran.*') => 'transaksi',
        request()->routeIs('pelanggan.akun', 'profile.*') => 'akun',
        default => null,
    };

    // Urutan tampil kiri → kanan. Tombol paling kanan = Akun.
    $items = [
        'beranda' => [
            'label' => 'Beranda',
            'href'  => route('home'),
            'icon'  => 'home',
        ],
        'transaksi' => [
            'label' => 'Riwayat',
            'href'  => route('pelanggan.orders.index'),
            'icon'  => 'receipt',
        ],
        'pesan' => [
            'label' => 'Pesan',
            'href'  => route('pelanggan.pesan.index'),
            'icon'  => 'plus',
        ],
        'keranjang' => [
            'label' => 'Keranjang',
            'href'  => route('pelanggan.cart.index'),
            'icon'  => 'cart',
        ],
        'akun' => [
            'label' => 'Akun',
            'href'  => route('pelanggan.akun'),
            'icon'  => 'user',
        ],
    ];
@endphp

<nav
    class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 print:hidden"
    style="padding-bottom: env(safe-area-inset-bottom, 0px);"
    aria-label="Navigasi utama"
>
    <ul class="max-w-md mx-auto grid grid-cols-5 items-end">
        @foreach ($items as $key => $item)
            @php $isActive = $active === $key; @endphp

            @if ($key === 'pesan')
                {{-- Tombol utama: bulat, menonjol ke atas, dengan ikon plus --}}
                <li class="flex flex-col items-center pb-2.5 text-[11px] font-semibold {{ $isActive ? 'text-primary-700' : 'text-slate-500' }}">
                    <a
                        href="{{ $item['href'] }}"
                        @if ($isActive) aria-current="page" @endif
                        aria-label="Pesan layanan laundry"
                        class="-mt-6 mb-0.5 flex h-14 w-14 items-center justify-center rounded-full text-white shadow-lg shadow-primary-700/30 ring-4 ring-white transition-all active:scale-95 focus-visible:outline-none focus-visible:ring-primary-200 {{ $isActive ? 'bg-primary-800' : 'bg-primary-700 hover:bg-primary-800' }}"
                    >
                        <x-icon :name="$item['icon']" class="w-7 h-7" />
                    </a>
                    <span>{{ $item['label'] }}</span>
                </li>
            @else
                <li>
                    <a
                        href="{{ $item['href'] }}"
                        @if ($isActive) aria-current="page" @endif
                        @if ($key === 'keranjang' && $cartCount > 0) aria-label="Keranjang ({{ $cartCount }} layanan)" @endif
                        class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold transition-colors {{ $isActive ? 'text-primary-700' : 'text-slate-500 hover:text-primary-700' }}"
                    >
                        <span class="relative">
                            <x-icon :name="$item['icon']" class="w-6 h-6" />
                            @if ($key === 'keranjang' && $cartCount > 0)
                                <span class="absolute -top-1.5 -right-2 min-w-4 h-4 px-1 rounded-full bg-amber-400 text-slate-900 text-[9px] font-extrabold leading-4 text-center" aria-hidden="true">{{ $cartCount }}</span>
                            @endif
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
