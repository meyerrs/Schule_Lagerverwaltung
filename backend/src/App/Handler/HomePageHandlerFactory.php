<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Doctrine\ORM\EntityManager;

use function assert;

final class HomePageHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new HomePageHandler(
            $container->get(EntityManager::class),
        );
    }
}
