<?php

declare(strict_types=1);

use App\Handler\AuthenticationHandler;
use App\Handler\InventoryCreateHandler;
use App\Handler\InventoryDeleteHandler;
use App\Handler\InventoryEditHandler;
use App\Handler\InventoryFetchHandler;
use App\Handler\LoginHandler;
use App\Handler\LogoutHandler;
use App\Handler\StatusFetchHandler;
use App\Handler\UserCreateHandler;
use App\Handler\UserDeleteHandler;
use App\Handler\UserEditHandler;
use App\Handler\UserFetchHandler;
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
            App\Handler\InventoryFetchHandler::class => function(ContainerInterface $container) {
                return new InventoryFetchHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\InventoryDeleteHandler::class => function(ContainerInterface $container) {
                return new InventoryDeleteHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\InventoryEditHandler::class => function(ContainerInterface $container) {
                return new InventoryEditHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\UserFetchHandler::class => function(ContainerInterface $container) {
                return new UserFetchHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\UserEditHandler::class => function(ContainerInterface $container) {
                return new UserEditHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\InventoryCreateHandler::class => function(ContainerInterface $container) {
                return new InventoryCreateHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\UserCreateHandler::class => function(ContainerInterface $container) {
                return new UserCreateHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\StatusFetchHandler::class => function(ContainerInterface $container) {
                return new StatusFetchHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
                );
            },
            App\Handler\UserDeleteHandler::class => function(ContainerInterface $container) {
                return new UserDeleteHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(EntityManagerInterface::class)
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