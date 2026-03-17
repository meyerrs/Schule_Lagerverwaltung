<?php

namespace App\Handler;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserEditHandler implements RequestHandlerInterface
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
            return $this->responseFactory->createResponse(500);
        }
        $json = $request->getBody()->getContents();
        $requestBody = json_decode($json, true);

        if (!is_int($requestBody['id'])) {
            $this->responseFactory->createResponse(400);
        }

        $item = $this->entityManager->find(User::class, $requestBody['id']);

        if (!$item instanceof User) {
            return $this->responseFactory->createResponse(500);
        }

        $item->setFirstname($requestBody['firstname'] ?? $item->getFirstname());
        $item->setLastname($requestBody['lastname'] ?? $item->getLastname());
        $item->setUsername($requestBody['username'] ?? $item->getUsername());
        $item->setPassword($requestBody['password'] ?? $item->getPassword());

        $roles = $requestBody['roles'];
        foreach($roles as $role) {
            $role = $this->entityManager->find(Role::class, $role['id']);

            $item->addRole($role);
        }

        $this->entityManager->flush();
        
        return $this->responseFactory->createResponse(200);
    }
}