<?php
// In config/autoload/cors.global.php
declare(strict_types=1);

use Mezzio\Cors\Configuration\ConfigurationInterface;

return [
    ConfigurationInterface::CONFIGURATION_IDENTIFIER => [
        'allowedOrigins' => ['http://localhost:5173'], 
        'allowedHeaders' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Cookie'], 
        'allowedMaxAge' => '600', 
        'credentialsAllowed' => true, 
        'exposedHeaders' => ['X-Custom-Header', 'Set-Cookies'],
    ],
];