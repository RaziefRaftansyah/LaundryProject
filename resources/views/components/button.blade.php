@props(['variant'=>'primary','size'=>'md','type'=>'button','href'=>null,'loading'=>false])
@php
$base='inline-flex items-center justify-center font-semibold rounded-lg transition-colors focus-visible:outline-none disabled:opacity-60 disabled:cursor-not-allowed select-none';
$sizes=['sm'=>'text-xs px-3 py-2 min-h-12 gap-1.5','md'=>'text-sm px-4 py-3 min-h-12 gap-2','lg'=>'text-base px-5 py-3.5 min-h-12 gap-2.5'];
$variants=['primary'=>'bg-[#D91B20] hover:bg-[#B71419] text-[#FAFAFA] shadow-sm','secondary'=>'border-2 border-[#D91B20] bg-[#FAFAFA] hover:bg-[#FDECEC] text-[#8F0F13]','outline'=>'border-2 border-[#D91B20] bg-[#FAFAFA] hover:bg-[#FDECEC] text-[#8F0F13]','danger'=>'bg-[#D91B20] hover:bg-[#B71419] text-[#FAFAFA]','ghost'=>'text-[#5C4A4B] hover:bg-[#FDECEC]'];
$classes="$base ".($sizes[$size]??$sizes['md']).' '.($variants[$variant]??$variants['primary']);
@endphp
@if($href)<a href="{{ $href }}" {{ $attributes->merge(['class'=>$classes]) }}>{{ $slot }}</a>@else<button type="{{ $type }}" {{ $attributes->merge(['class'=>$classes]) }}>{{ $slot }}</button>@endif
