@ -1,32 +0,0 @@
<?php

declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'driverClass' => Driver::class,
                    'host'        => '127.0.0.1',
                    'user'        => 'admin',
                    'password'    => 'password', // Dein Passwort hier eintragen
                    'dbname'      => 'schule_lagerverwaltung', // Dein DB-Name hier eintragen
                    'charset'     => 'utf8mb4',
                ],
            ],
        ],
        'driver' => [
            // Dies ist der Teil, den die Fehlermeldung vermisst hat:
            'orm_default' => [
                'class' => AttributeDriver::class,
                'paths' => [
                    __DIR__ . '/../../src/App/Entity',
                ],
            ],
        ],
    ],
];