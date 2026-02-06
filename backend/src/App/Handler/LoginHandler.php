<?php

namespace App\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Session\SessionInterface;
use Psalm\Report\JsonReport;
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
        $json = $request->getBody()->getContents();
        $body = json_decode($json, true);

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
        
        $date = new \DateTimeImmutable('+20 minutes');
        $session->set('user_id', $user->getId());
        $session->set('experation_timestamp', $date->getTimestamp());
        
        return $this->responseFactory->createResponse(200);
    }
}