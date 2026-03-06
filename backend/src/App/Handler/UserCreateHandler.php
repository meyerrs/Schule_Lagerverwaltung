<?php

namespace App\Handler;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserCreateHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $json = $request->getBody()->getContents();
        $requestBody = json_decode($json, true);

        $user = new User();

        $user->setFirstname($requestBody['firstname'] ?? null);
        $user->setLastname($requestBody['lastname'] ?? null);
        $user->setUsername($requestBody['username'] ?? null);
        $user->setPassword($requestBody['password'] ?? null);

        if (!is_array($requestBody['roles'] ?? null)) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->responseFactory->createResponse(200);
        }

        $roles = $requestBody['roles'];
        foreach($roles as $role) {
            $role = $this->entityManager->find(Role::class, $role['id']);

            $user->addRole($role);
        }
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->responseFactory->createResponse(200);
    }
}