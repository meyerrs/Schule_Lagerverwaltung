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

class UserDeleteHandler implements RequestHandlerInterface
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
        $body = json_decode($json, true);

        if (!isset($body['id'])) {
            return $this->responseFactory->createResponse(400);
        }
        $id = $body['id'];

        $user = $this->entityManager->find(User::class, $id);

        if (!$user) {
            return $this->responseFactory->createResponse(500);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->responseFactory->createResponse(200);
    }

}