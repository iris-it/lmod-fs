<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Base Paths
    |--------------------------------------------------------------------------
    |a
    | This option is useful when there is other packages where and those use
    | the same signature than this package
    |
    */

    'base_path' => 'files',
    'api_path' => 'api',
    'admin_path' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Mounts
    |--------------------------------------------------------------------------
    |
    | This option is to set the mounts path and their behaviors
    | mounts has to be created ( except test ) in filesystem
    | configuration first in order to match the name
    |
    */

    'mounts' => [
        'group' => [
            'root' => '/var/data/group'
        ],
        'user' => [
            'root' => '/var/data/user'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed roles for admin section
    |--------------------------------------------------------------------------
    |
    | Roles for admin section, separated by commas
    |
    */

    'admin_allowed_roles' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Base User Class it must contains
    |--------------------------------------------------------------------------
    |
    | Roles for admin section, separated by commas
    |
    */

    'admin_allowed_roles' => 'admin',

];