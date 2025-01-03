<?php

return [
    /**
     * The disk on which to store signature images. Choose one or more of
     * the disks you've configured in config/filesystems.php.
     */
    'disk_name' => env('SIGNATURES_DISK', 'local'),

    /**
     * Encrypt the stored files on the disk using the laravel native Crypt methods.
     * Attention: Changing this, destroys all files that were created before.
     */
    'encrypt_files' => false,

    /**
     * Path where the signature images will be stored.
     */
    'signatures_path' => 'signatures',

    /**
     * Route name where you want to redirect users after signing.
     */
    'redirect_route_name' => null,

    /**
     * Width and height of the signature rectangle.
     */
    'width' => 600,
    'height' => 200,
];
