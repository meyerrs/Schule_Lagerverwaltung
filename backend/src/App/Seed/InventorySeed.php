<?php

declare(strict_types=1);

namespace App\Seed;

use App\Entity\Inventory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class InventorySeed implements SeedInterface
{
    public function name(): string
    {
        return 'inventory';
    }

    public function run(EntityManagerInterface $entityManager, string $profile): void
    {
        $userRepository = $entityManager->getRepository(User::class);
        $inventoryRepository = $entityManager->getRepository(Inventory::class);

        $owner = $userRepository->findOneBy(['username' => 'lehrer.demo']);
        if (! $owner instanceof User) {
            throw new \RuntimeException('Seed user "lehrer.demo" must exist before inventory seeding.');
        }

        $items = [
            [
                'name' => 'Multimeter',
                'abteilung' => 'Elektro',
                'gruppe' => 'Messgeraete',
                'fach' => 'E1',
                'ort' => 'Werkraum 1',
                'owner' => 'lehrer.demo',
            ],
            [
                'name' => 'Loetstation',
                'abteilung' => 'Elektro',
                'gruppe' => 'Werkzeug',
                'fach' => 'E2',
                'ort' => 'Werkraum 1',
                'owner' => 'lehrer.demo',
            ],
        ];

        if (SeedProfile::includes($profile, SeedProfile::DEMO)) {
            $items[] = [
                'name' => '3D-Drucker',
                'abteilung' => 'Informatik',
                'gruppe' => 'Hardware',
                'fach' => 'I5',
                'ort' => 'Labor 2',
                'owner' => 'admin',
            ];
            $items[] = [
                'name' => 'Raspberry Pi Kit',
                'abteilung' => 'Informatik',
                'gruppe' => 'Lehrmittel',
                'fach' => 'I2',
                'ort' => 'Labor 2',
                'owner' => 'admin',
            ];
        }

        foreach ($items as $seededItem) {
            $owner = $userRepository->findOneBy(['username' => $seededItem['owner']]);
            if (! $owner instanceof User) {
                throw new \RuntimeException(
                    sprintf('Seed user "%s" must exist before inventory seeding.', $seededItem['owner'])
                );
            }

            $inventory = $inventoryRepository->findOneBy([
                'name' => $seededItem['name'],
                'fach' => $seededItem['fach'],
                'ort' => $seededItem['ort'],
            ]);

            if (! $inventory instanceof Inventory) {
                $inventory = new Inventory();
                $entityManager->persist($inventory);
            }

            $inventory->setName($seededItem['name']);
            $inventory->setAbteilung($seededItem['abteilung']);
            $inventory->setGruppe($seededItem['gruppe']);
            $inventory->setFach($seededItem['fach']);
            $inventory->setOrt($seededItem['ort']);
            $inventory->setVerantwortlicher($owner);
        }
    }
}
