<?php

return [
    'session_config' => [
        'cookie_httponly' => true,
        'cookie_secure'   => false, // Muss auf false sein, wenn du KEIN https nutzt (lokal)
        'cookie_samesite' => 'Lax',   // 'Lax' erlaubt das Senden bei Cross-Origin Requests in modernen Browsern
    ],
];