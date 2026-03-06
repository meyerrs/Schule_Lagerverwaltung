<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserFetchHandler implements RequestHandlerInterface
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
            $users = $this->entityManager->createQueryBuilder()
                ->select('partial u.{id, firstname, lastname, username}')
                ->from(User::class, 'u')
                ->getQuery()
                ->getArrayResult();

            return new JsonResponse($users);
        }
        $id = $params['id'];
        $user = $this->entityManager->createQueryBuilder()
            ->select('partial u.{id, firstname, lastname, username}')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->from(User::class, 'u')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($user);
    }
}