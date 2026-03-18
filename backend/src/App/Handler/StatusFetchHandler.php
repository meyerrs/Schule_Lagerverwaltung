<?php

namespace App\Handler;

use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StatusFetchHandler implements RequestHandlerInterface
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
            $status = $this->entityManager->createQueryBuilder()
                ->select('s')
                ->from(Status::class, 's')
                ->getQuery()
                ->getArrayResult();

            return new JsonResponse($status);
        }

        $id = $params['id'];
        $status = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->from(Status::class, 's')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($status);
    }
}