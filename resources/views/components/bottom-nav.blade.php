@props(['active'=>null])
@php
$cartCount=auth()->user()?->isPelanggan()?auth()->user()->cartItems()->count():0;
$items=[
 'beranda'=>['label'=>'Beranda','href'=>route('home'),'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
 'transaksi'=>['label'=>'Transaksi','href'=>route('pelanggan.orders.index'),'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-1 0a2 2 0 01-2 2H9a2 2 0 01-2-2m3 7h3m-3 4h3'],
 'pesan'=>['label'=>'Pesan','href'=>route('pelanggan.pesan.index'),'icon'=>'M12 5v14M5 12h14'],
 'keranjang'=>['label'=>'Keranjang','href'=>route('pelanggan.cart.index'),'icon'=>'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2h12m-9 4a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z'],
 'akun'=>['label'=>'Akun','href'=>route('pelanggan.akun'),'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
];
@endphp
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-[#E8CBCD] print:hidden" style="padding-bottom:env(safe-area-inset-bottom,0px)" aria-label="Navigasi utama"><ul class="max-w-3xl mx-auto grid grid-cols-5 items-end px-1">@foreach($items as $key=>$item) @php($isActive=$active===$key)<li class="flex justify-center"><a href="{{ $item['href'] }}" class="relative flex w-full min-h-12 flex-col items-center justify-center gap-1 py-2.5 text-[10px] font-semibold {{ $isActive?'text-[#D91B20]':'text-[#5C4A4B] hover:text-[#D91B20]' }}">@if($key==='pesan')<span class="-mt-6 flex h-14 w-14 items-center justify-center rounded-full border-4 border-white bg-[#D91B20] text-white shadow-lg"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"/></svg></span><span>{{ $item['label'] }}</span>@else<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive?'2.2':'1.8' }}" d="{{ $item['icon'] }}"/></svg><span>{{ $item['label'] }}</span>@if($key==='keranjang'&&$cartCount>0)<span class="absolute right-2 top-1 min-w-5 rounded-full bg-[#B34700] px-1 text-center text-[10px] leading-5 text-white">{{ $cartCount }}</span>@endif @endif</a></li>@endforeach</ul></nav>
