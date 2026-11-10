@props(['variant'=>null,'status'=>null,'payment'=>null,'size'=>'md','dot'=>true])
@php
$map=['pending'=>['bg-[#FDECEC] text-[#B34700] border-[#B34700]','Menunggu','◷'],'diproses'=>['bg-[#FFF5F5] text-[#B34700] border-[#B34700]','Diproses','◷'],'siap_diambil'=>['bg-[#F0F8F2] text-[#1B7F3B] border-[#1B7F3B]','Siap Diambil','✓'],'selesai'=>['bg-[#F0F8F2] text-[#1B7F3B] border-[#1B7F3B]','Selesai','✓'],'dibatalkan'=>['bg-[#FDECEC] text-[#D91B20] border-[#D91B20]','Dibatalkan','!'],'paid'=>['bg-[#F0F8F2] text-[#1B7F3B] border-[#1B7F3B]','Lunas','✓'],'failed'=>['bg-[#FDECEC] text-[#D91B20] border-[#D91B20]','Gagal','!'],'expired'=>['bg-[#FFF5F5] text-[#5C4A4B] border-[#5C4A4B]','Kadaluarsa','!']];
$key=$status??$payment;$item=$map[$key]??['bg-[#FFF5F5] text-[#5C4A4B] border-[#E8CBCD]','Status','•'];$sizeClass=$size==='sm'?'text-[11px] px-2 py-1':'text-xs px-2.5 py-1.5';
@endphp
<span {{ $attributes->merge(['class'=>"inline-flex items-center gap-1.5 rounded-full border font-semibold {$sizeClass} {$item[0]}"]) }}>@if($dot)<span aria-hidden="true" class="font-bold">{{ $item[2] }}</span>@endif{{ $slot->isEmpty()?$item[1]:$slot }}</span>
