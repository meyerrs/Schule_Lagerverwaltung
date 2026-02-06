<?php

return [
    // Laminas Session Config (wird von der Factory oft hier erwartet)
    'session_config' => [
        'cookie_httponly' => true,
        'cookie_secure'   => false,
        'cookie_samesite' => 'Lax',
    ],
    // Manche Mezzio-Setups brauchen es zusätzlich flach im 'session' Key
    'session' => [
        'persistence' => [
            'ext' => [
                'cookie_samesite' => 'Lax',
            ],
        ],
    ],
];