<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        if (
            !isset($body['username'])
            || !isset($body['password'])
        ) {
            return $this->responseFactory->createResponse(400);
        }
        $username = $body['username'];
        $password = $body['password'];

        $session = $request->getAttribute(SessionInterface::class);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(["username" => $username]);

        if (
            !isset($user)
            || $user->getPassword() !== $password
        ) {
            return $this->responseFactory->createResponse(401);
        }

        $session->set('user_id', $user->getId());
        
        return $this->responseFactory->createResponse(200);
    }
}