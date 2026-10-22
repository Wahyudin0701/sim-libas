<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Seeder Password
    |--------------------------------------------------------------------------
    |
    | Password bawaan untuk akun yang dibentuk melalui database seeder pada lingkungan
    | non-production (local, development, testing).
    | Di lingkungan production, sistem mewajibkan pengisian password kuat melalui .env
    | demi mencegah celah keamanan akun pengurus bawaan.
    |
    */
    'default_password' => env('SEEDER_DEFAULT_PASSWORD', 'password'),

    /*
    |--------------------------------------------------------------------------
    | Kredensial Akun Pejabat & Pengurus RT 15 SIM-LiBas
    |--------------------------------------------------------------------------
    |
    | Setiap akun pengurus membaca kredensial dari environment variable (.env).
    | Password dapat diatur secara serentak via SEEDER_DEFAULT_PASSWORD
    | atau secara spesifik per akun pengurus.
    |
    */
    'accounts' => [
        'super_admin' => [
            'name' => env('SEEDER_ADMIN_NAME', 'Administrator SIM-LiBas'),
            'email' => env('SEEDER_ADMIN_EMAIL', 'admin@simlibas.com'),
            'phone' => env('SEEDER_ADMIN_PHONE', '0811-0000-0001'),
            'password' => env('SEEDER_ADMIN_PASSWORD'),
            'roles' => ['super_admin'],
        ],

        'ketua_rt' => [
            'name' => env('SEEDER_RT_NAME', 'Bpk. H. Ahmad Sudirman, S.E.'),
            'email' => env('SEEDER_RT_EMAIL', 'rt@simlibas.com'),
            'phone' => env('SEEDER_RT_PHONE', '0812-7400-1501'),
            'password' => env('SEEDER_RT_PASSWORD'),
            'roles' => ['ketua_rt'],
        ],

        'sekretaris' => [
            'name' => env('SEEDER_SEKRETARIS_NAME', 'Bpk. Bambang Irawan, S.Kom.'),
            'email' => env('SEEDER_SEKRETARIS_EMAIL', 'sekretaris@simlibas.com'),
            'phone' => env('SEEDER_SEKRETARIS_PHONE', '0812-7400-1502'),
            'password' => env('SEEDER_SEKRETARIS_PASSWORD'),
            'roles' => ['sekretaris'],
        ],

        'bendahara' => [
            'name' => env('SEEDER_BENDAHARA_NAME', 'Ibu Hj. Nurhayati, S.E.'),
            'email' => env('SEEDER_BENDAHARA_EMAIL', 'bendahara@simlibas.com'),
            'phone' => env('SEEDER_BENDAHARA_PHONE', '0812-7400-1503'),
            'password' => env('SEEDER_BENDAHARA_PASSWORD'),
            'roles' => ['bendahara'],
        ],

        'banksampah' => [
            'name' => env('SEEDER_BANKSAMPAH_NAME', 'Bpk. Joko Wahyudi'),
            'email' => env('SEEDER_BANKSAMPAH_EMAIL', 'banksampah@simlibas.com'),
            'phone' => env('SEEDER_BANKSAMPAH_PHONE', '0812-7400-1504'),
            'password' => env('SEEDER_BANKSAMPAH_PASSWORD'),
            'roles' => ['koordinator_bank_sampah', 'warga'],
        ],

        'keamanan' => [
            'name' => env('SEEDER_KEAMANAN_NAME', 'Bpk. Hendra Saputra'),
            'email' => env('SEEDER_KEAMANAN_EMAIL', 'keamanan@simlibas.com'),
            'phone' => env('SEEDER_KEAMANAN_PHONE', '0812-7400-1505'),
            'password' => env('SEEDER_KEAMANAN_PASSWORD'),
            'roles' => ['koordinator_keamanan', 'warga'],
        ],

        'rw' => [
            'name' => env('SEEDER_RW_NAME', 'Bpk. H. Suryadi, S.Sos.'),
            'email' => env('SEEDER_RW_EMAIL', 'rw@simlibas.com'),
            'phone' => env('SEEDER_RW_PHONE', '0812-7400-1599'),
            'password' => env('SEEDER_RW_PASSWORD'),
            'roles' => ['rw_kelurahan'],
        ],
    ],
];
