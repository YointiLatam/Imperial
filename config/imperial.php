<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Superadministrador inicial
    |--------------------------------------------------------------------------
    |
    | Lo crea SuperadminSeeder. Las credenciales vienen del .env para no
    | guardar contraseñas en el repositorio. Los demás datos (DNI, teléfono)
    | se completan luego desde "Mi perfil".
    |
    */

    'admin' => [
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
