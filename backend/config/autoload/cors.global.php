<?php
return [
    'cors' => [
        "origin" => ["http://localhost:3000"], // Hier DEINE Frontend-URL eintragen
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
        "headers.allow" => ["Authorization", "Content-Type", "X-Requested-With"],
        "headers.expose" => [],
        "credentials" => true, 
        "cache" => 0,
    ],
];