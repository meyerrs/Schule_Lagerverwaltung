<?php

declare(strict_types=1);

namespace App\Container;

use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\ORMSchemaTemplateProvider;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class MigrationsFactory
{
    public function __invoke(ContainerInterface $container): DependencyFactory
    {
        $config = $container->get('config')['doctrine']['migrations'] ?? [];
        $entityManager = $container->get(EntityManagerInterface::class);

        $dependencyFactory = DependencyFactory::fromConnection(
            new ConfigurationArray($config),
            new ExistingConnection($entityManager->getConnection())
        );

        // HIER IST DIE LÖSUNG:
        // Wir registrieren den EntityManager als Schema-Quelle
        $dependencyFactory->setService(
            \Doctrine\Migrations\Provider\SchemaProvider::class,
            new \Doctrine\Migrations\Provider\OrmSchemaProvider($entityManager)
        );

        return $dependencyFactory;
    }
}