<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Session\SessionInterface;
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
        $session = $request->getAttribute(SessionInterface::class);
        $roles = $session->get('roles');
        if (!is_array($roles) || !in_array('admin', $roles)) {
            $user = $this->entityManager->createQueryBuilder()
            ->select('partial u.{id, firstname, lastname}')
            ->from(User::class, 'u')
            ->getQuery()
            ->getArrayResult();
            return new JsonResponse($user);
        }
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            $users = $this->entityManager->createQueryBuilder()
                ->select('u', 'r')
                ->from(User::class, 'u')
                ->leftJoin('u.roles', 'r')
                ->getQuery()
                ->getArrayResult();

            return new JsonResponse($users);
        }
        $id = $params['id'];
        $user = $this->entityManager->createQueryBuilder()
            ->select('u', 'r')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->from(User::class, 'u')
            ->leftJoin('u.roles', 'r')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($user);
    }
}