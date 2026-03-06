<?php

namespace App\Handler;

use App\Entity\Inventory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InventoryCreateHandler implements RequestHandlerInterface
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

        $item = new Inventory();

        $item->setName($requestBody['name']);
        $item->setAbteilung($requestBody['abteilung']);
        $item->setGruppe($requestBody['gruppe']);
        $item->setFach($requestBody['fach']);
        $item->setOrt($requestBody['ort']);
        
        if (!is_int($requestBody['verantwortlicher'])) {
            $this->entityManager->flush();
            return $this->responseFactory->createResponse(200);
        }

        $user = $this->entityManager->find(User::class, $requestBody['verantwortlicher']);

        if (!$user instanceof User) {
            return $this->responseFactory->createResponse(500);
        }

        $item->setVerantwortlicher($user);
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
}