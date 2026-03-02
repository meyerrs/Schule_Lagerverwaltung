<?php
declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

$env = static function (string $key, ?string $default = null): ?string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value !== false && $value !== null && $value !== '') {
        return (string) $value;
    }

    static $dotenv = null;
    if ($dotenv === null) {
        $dotenvPath = dirname(__DIR__, 2) . '/.env';
        $dotenv = is_file($dotenvPath) ? (parse_ini_file($dotenvPath, false, INI_SCANNER_RAW) ?: []) : [];
    }

    return isset($dotenv[$key]) && $dotenv[$key] !== ''
        ? (string) $dotenv[$key]
        : $default;
};

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'driverClass' => Driver::class,
                    'host'        => $env('DATABASE_HOST', '127.0.0.1'),
                    'port'        => (int) $env('DATABASE_PORT', '3306'),
                    'user'        => $env('DATABASE_USER', 'admin'),
                    'password'    => $env('DATABASE_PASSWORD', ''),
                    'dbname'      => $env('DATABASE_NAME', ''),
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
