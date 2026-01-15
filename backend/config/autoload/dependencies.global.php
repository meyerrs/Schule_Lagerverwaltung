<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Roave\PsrContainerDoctrine\EntityManagerFactory;

return [
    'dependencies' => [
        'factories' => [
            // Hier passiert die Magie:
            EntityManagerInterface::class => EntityManagerFactory::class,
            \Doctrine\Migrations\DependencyFactory::class => \App\Container\MigrationsFactory::class,
        ],
    ],
];