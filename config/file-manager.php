<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Only files with these MIME types are allowed for upload.
    | Use '*' to allow all within a category (e.g. 'image/*').
    |
    */
    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'text/csv',
        'application/zip',
        'application/json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Extensions (for frontend accept attribute)
    |--------------------------------------------------------------------------
    |
    */
    'allowed_extensions' => 'jpg,jpeg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,txt,csv,zip,json',
];
