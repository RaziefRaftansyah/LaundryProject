<?php
namespace App\Support;
final class PhoneNumber { public static function tryNormalize(?string $value): ?string { $digits=preg_replace('/\D/','',(string)$value); if($digits==='')return null; $local=str_starts_with($digits,'62')?substr($digits,2):(str_starts_with($digits,'0')?substr($digits,1):$digits); return preg_match('/^8\d{8,12}$/',$local)?'+62'.$local:null; } }
