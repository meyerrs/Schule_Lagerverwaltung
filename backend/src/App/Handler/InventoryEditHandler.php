<?php

namespace App\Handler;

use App\Entity\Inventory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InventoryEditHandler implements RequestHandlerInterface
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

        if (!is_int($requestBody['id'])) {
            $this->responseFactory->createResponse(400);
        }

        $item = $this->entityManager->find(Inventory::class, $requestBody['id']);

        if (!$item instanceof Inventory) {
            return $this->responseFactory->createResponse(500);
        }

        $item->setName($requestBody['name'] ?? $item->getName());
        $item->setAbteilung($requestBody['abteilung'] ?? $item->getAbteilung());
        $item->setGruppe($requestBody['gruppe'] ?? $item->getGruppe());
        $item->setFach($requestBody['fach'] ?? $item->getFach());
        $item->setOrt($requestBody['ort'] ?? $item->getOrt());

        if (!is_int($requestBody['verantwortlicherId'])) {
            $this->entityManager->flush();
            return $this->responseFactory->createResponse(200);
        }

        $user = $this->entityManager->find(User::class, $requestBody['verantwortlicherId']);

        if (!$user instanceof User) {
            return $this->responseFactory->createResponse(500);
        }

        $item->setVerantwortlicher($user);
        $this->entityManager->flush();
        
        return $this->responseFactory->createResponse(200);
    }
}