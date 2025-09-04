<?php

return [
    'show_warnings' => false,
    'public_path' => null,
    'convert_entities' => true,

    'options' => [
        'font_dir' => storage_path('fonts'),
        'font_cache' => storage_path('fonts'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),

        // Pengaturan penting untuk encoding dan parser
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,

        'default_font' => 'Poppins',

        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'dpi' => 96,
        'enable_font_subsetting' => false,
        'enable_php' => false,
        'enable_javascript' => true,
        'font_height_ratio' => 1.1,
    ],
];
