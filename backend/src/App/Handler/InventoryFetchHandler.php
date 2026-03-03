<?php

namespace App\Handler;

use App\Entity\Inventory;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InventoryFetchHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (
            !isset($params['id'])
        ) {
            $inventory = $this->entityManager->createQueryBuilder()
                ->select('i')
                ->from(\App\Entity\Inventory::class, 'i')
                ->getQuery()
                ->getArrayResult();
            return new JsonResponse($inventory);
        }
        $id = $params['id'];
        $inventory = $this->entityManager->createQueryBuilder()
                ->select('i')
                ->where('i.id = :id')
                ->setParameter('id', $id)
                ->from(\App\Entity\Inventory::class, 'i')
                ->getQuery()
                ->getArrayResult();

        return new JsonResponse($inventory);
    }
}