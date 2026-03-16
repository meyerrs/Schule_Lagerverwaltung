<?php

namespace App\Handler;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RoleFetchHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            $roles = $this->entityManager->createQueryBuilder()
                ->select('r')
                ->from(Role::class, 'r')
                ->getQuery()
                ->getArrayResult();

            return new JsonResponse($roles);
        }

        $roles = $this->entityManager->createQueryBuilder()
                ->select('r')
                ->from(Role::class, 'r')
                ->where('r.id = :id')
                ->setParameter('id', $params['id'])
                ->getQuery()
                ->getArrayResult();

        return new JsonResponse($roles);
    }
}
