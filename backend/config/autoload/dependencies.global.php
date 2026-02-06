<?php

declare(strict_types=1);

use App\Handler\AuthenticationHandler;
use App\Handler\LoginHandler;
use App\Handler\LogoutHandler;
use App\Middleware\AuthenticationMiddleware;
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

            // Handler
            App\Handler\LoginHandler::class => function(ContainerInterface $container) {
                return new LoginHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\AuthenticationHandler::class => function(ContainerInterface $container) {
                return new AuthenticationHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\LogoutHandler::class => function(ContainerInterface $container) {
                return new LogoutHandler(
                    $container->get(ResponseFactoryInterface::class)
                );
            },

            //Middleware
            App\Middleware\AuthenticationMiddleware::class => function(ContainerInterface $container) {
                return new AuthenticationMiddleware(
                    $container->get(ResponseFactoryInterface::class)
                );
            },
        ],
    ],
];