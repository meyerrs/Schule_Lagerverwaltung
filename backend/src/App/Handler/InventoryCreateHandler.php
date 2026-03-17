<?php

namespace App\Handler;

use App\Entity\Inventory;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Session\SessionInterface;
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
        $session = $request->getAttribute(SessionInterface::class);
        $roles = $session->get('roles');
        if (!is_array($roles) || (!in_array('admin', $roles) && !in_array('inventarAdmin', $roles))) {
            return $this->responseFactory->createResponse(500);
        }
        $json = $request->getBody()->getContents();
        $requestBody = json_decode($json, true);

        $item = new Inventory();

        $item->setName($requestBody['name']);
        $item->setAbteilung($requestBody['abteilung']);
        $item->setGruppe($requestBody['gruppe']);
        $item->setFach($requestBody['fach']);
        $item->setOrt($requestBody['ort']);
        
        if (!is_int($requestBody['verantwortlicher'])) {
            $this->entityManager->persist($item);
            $this->entityManager->flush();
            return $this->responseFactory->createResponse(200);
        }

        if (is_int($requestBody['status'])) {
            $status = $this->entityManager->find(Status::class, $requestBody['status']);

            if (!$status instanceof Status) {
                return $this->responseFactory->createResponse(500);
            }
            $item->setStatus($status);
        }

        $user = $this->entityManager->find(User::class, $requestBody['verantwortlicher']);

        if (!$user instanceof User) {
            return $this->responseFactory->createResponse(500);
        }

        $item->setVerantwortlicher($user);
        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $this->responseFactory->createResponse(200);
    }
}