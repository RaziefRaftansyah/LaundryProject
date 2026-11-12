@props([
    'current' => 'pilih', // pilih, detail, bayar, selesai
])

@php
    $steps = [
        'pilih'  => 'Pilih Layanan',
        'detail' => 'Detail & Konfirmasi',
        'bayar'  => 'Pembayaran',
    ];
    $keys = array_keys($steps);
    // "selesai" = semua langkah tercentang
    $currentIndex = $current === 'selesai' ? count($keys) : array_search($current, $keys, true);
@endphp

<ol
    {{ $attributes->merge(['class' => 'grid grid-cols-3 gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm sm:gap-3 sm:p-3']) }}
    aria-label="Tahapan pemesanan"
>
    @foreach ($steps as $key => $label)
        @php
            $i = $loop->index;
            $state = $i < $currentIndex ? 'done' : ($i === $currentIndex ? 'current' : 'next');
        @endphp
        <li
            @if ($state === 'current') aria-current="step" @endif
            @class([
                'flex min-w-0 items-center gap-2 rounded-xl px-2 py-2 sm:px-3',
                'bg-primary-50' => $state === 'current',
            ])
        >
            <span @class([
                'flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-extrabold sm:h-10 sm:w-10',
                'bg-primary-700 text-white ring-4 ring-primary-100' => $state === 'current',
                'bg-emerald-600 text-white' => $state === 'done',
                'bg-slate-100 text-slate-700' => $state === 'next',
            ])>
                @if ($state === 'done')
                    <x-icon name="check" class="h-4 w-4" />
                @else
                    {{ $i + 1 }}
                @endif
            </span>
            <span @class([
                'min-w-0 truncate text-xs font-bold sm:text-sm',
                'text-slate-900' => $state === 'current',
                'text-slate-700' => $state === 'done',
                'text-slate-700' => $state === 'next',
            ])>{{ $label }}</span>
        </li>
    @endforeach
</ol>
