<?php

declare(strict_types=1);

use App\Handler\LoginHandler;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Roave\PsrContainerDoctrine\EntityManagerFactory;

return [
    'dependencies' => [
        'factories' => [
            // Hier passiert die Magie:
            EntityManagerInterface::class => EntityManagerFactory::class,
            \Doctrine\Migrations\DependencyFactory::class => \App\Container\MigrationsFactory::class,
            App\Handler\LoginHandler::class => function(ContainerInterface $container) {
                return new LoginHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
        ],
    ],
];