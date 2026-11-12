<?php

namespace App\Support;

/**
 * Deteksi nilai config yang masih berupa placeholder "[ISI ...]".
 * Dipakai view publik (footer, area) agar tidak menampilkan data karangan.
 */
class Placeholder
{
    public static function is(mixed $value): bool
    {
        return trim((string) $value) === '' || str_contains((string) $value, '[ISI');
    }
}
