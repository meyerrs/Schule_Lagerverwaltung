<?php

namespace App\Handler;

use App\Entity\Inventory;
use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InventoryDeleteHandler implements RequestHandlerInterface
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
        if (!is_array($roles) || (!in_array('admin', $roles) && !in_array('inventarAdmin', $roles))) {
            return $this->responseFactory->createResponse(500);
        }
        $json = $request->getBody()->getContents();
        $body = json_decode($json, true);

        if (!isset($body['id'])) {
            return $this->responseFactory->createResponse(400);
        }
        $id = $body['id'];

        $item = $this->entityManager->find(Inventory::class, $id);

        if (!$item) {
            return $this->responseFactory->createResponse(500);
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return $this->responseFactory->createResponse(200);
    }
}
