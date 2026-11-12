<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Informasi Toko / Outlet Laundry Coin
    |--------------------------------------------------------------------------
    | Konfigurasi ini digunakan pada halaman publik, landing page, footer, dan kontak.
    | Silakan sesuaikan nilai di file .env atau langsung edit nilai default di bawah ini.
    */

    'store_name' => env('LAUNDRY_STORE_NAME', 'Laundry Coin'),
    'tagline'    => env('LAUNDRY_TAGLINE', '[ISI TAGLINE TOKO]'),
    
    // Alamat dan Lokasi Outlet
    // Data kota, alamat, dan titik peta diambil dari listing "Laundry Coin" pada peta outlet
    // yang dipasang di beranda (Jl. PM. Noor, Sempaja, Samarinda Utara).
    'city'             => env('LAUNDRY_CITY', 'Samarinda'),
    'address'          => env('LAUNDRY_ADDRESS', 'Jl. PM. Noor No. 15b, Sempaja Selatan, Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119'),
    'service_area'     => env('LAUNDRY_SERVICE_AREA', '[ISI NAMA AREA / JANGKAUAN LAYANAN]'),
    // Titik koordinat sama dengan pin "Laundry Coin" pada peta embed di bawah.
    'google_maps_url'  => env('LAUNDRY_MAPS_URL', 'https://www.google.com/maps/search/?api=1&query=-0.4518989,117.1620591'),
    // Peta lokasi outlet (iframe hero beranda).
    'maps_embed_url'   => env('LAUNDRY_MAPS_EMBED_URL', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.6942242309133!2d117.15947881089576!3d-0.45189889954176055!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df679e11457daab%3A0x22987d217cc81316!2sLaundry%20Coin!5e0!3m2!1sid!2sid!4v1794472319735!5m2!1sid!2sid'),

    // Kontak Outlet
    'phone'      => env('LAUNDRY_PHONE', '[ISI NOMOR HP/TELEPON]'),
    'whatsapp'   => env('LAUNDRY_WHATSAPP', '[ISI NOMOR WHATSAPP]'), // format nomor: misal 628123456789
    // Nomor WA pegawai di nota pesanan pelanggan; kosong = nomor WhatsApp outlet di atas
    'staff_whatsapp' => env('LAUNDRY_STAFF_WHATSAPP') ?: env('LAUNDRY_WHATSAPP', '[ISI NOMOR WHATSAPP]'),
    'email'      => env('LAUNDRY_EMAIL', '[ISI EMAIL TOKO]'),

    // Zona waktu outlet: batas hari di laporan keuangan (aplikasi menyimpan waktu dalam UTC).
    'report_timezone' => env('LAUNDRY_REPORT_TIMEZONE', 'Asia/Jakarta'),

    // Zona waktu bisnis: tanggal bisnis ledger, shift, laporan harian/mingguan (ISO, Senin-Minggu)/bulanan.
    'timezone' => env('LAUNDRY_TIMEZONE', env('LAUNDRY_REPORT_TIMEZONE', 'Asia/Jakarta')),

    // Metode pembayaran online (cashless) yang ditampilkan ke pelanggan.
    // key = nilai kolom payments.method, label = teks yang tampil di website.
    'online_payment_methods' => [
        'transfer' => [
            'label'       => 'Transfer Bank',
            'description' => 'Transfer ke rekening resmi outlet melalui m-banking, internet banking, atau ATM.',
        ],
        'e-wallet' => [
            'label'       => 'E-Wallet / QRIS',
            'description' => 'Scan QRIS atau bayar lewat aplikasi dompet digital dan m-banking yang mendukung QRIS.',
        ],
    ],

    // Jenis cucian untuk form pemesanan + batas kapasitas per mesin (sama dengan info kapasitas di landing page).
    // key = nilai cart_items.item_type, capacity/unit dipakai pembantu "saran jumlah mesin".
    'item_types' => [
        'pakaian'  => ['label' => 'Pakaian Biasa', 'capacity' => 6, 'unit' => 'KG'],
        'sprei'    => ['label' => 'Sprei / Selimut Tipis', 'capacity' => 3, 'unit' => 'KG'],
        'bedcover' => ['label' => 'Bedcover', 'capacity' => 1, 'unit' => 'Pcs'],
        // Campuran memakai batas paling aman agar mesin tidak kelebihan muatan
        'campuran' => ['label' => 'Campuran', 'capacity' => 3, 'unit' => 'KG'],
    ],

    // Pesanan online: rencana antar cucian ke outlet maksimal H+N
    'drop_off_max_days' => 7,

    // Batas keranjang agar 1 transaksi tetap wajar
    'cart_max_lines' => 10,
    'max_machine_qty' => 20,

    // Jam Operasional
    'operating_hours' => [
        'weekday'    => env('LAUNDRY_HOURS_WEEKDAY', '[ISI JAM OPERASIONAL SENIN-JUMAT]'),
        'weekend'    => env('LAUNDRY_HOURS_WEEKEND', '[ISI JAM OPERASIONAL SABTU-MINGGU]'),
        'timezone'   => env('LAUNDRY_TIMEZONE_LABEL', '[ISI ZONA WAKTU]'),
        'last_order' => env('LAUNDRY_LAST_ORDER', '[ISI JAM LAST ORDER]'),
        'notes'      => env('LAUNDRY_HOURS_NOTES', '[ISI CATATAN OPERASIONAL]'),
    ],
];
