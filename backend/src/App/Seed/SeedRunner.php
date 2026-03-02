<?php

declare(strict_types=1);

namespace App\Seed;

use Doctrine\ORM\EntityManagerInterface;

final class SeedRunner
{
    /**
     * @param list<SeedInterface> $seeds
     */
    public function __construct(
        private array $seeds
    ) {
    }

    public function run(EntityManagerInterface $entityManager, string $profile): void
    {
        $profile = SeedProfile::normalize($profile);

        foreach ($this->seeds as $seed) {
            $seed->run($entityManager, $profile);
            $entityManager->flush();
            $entityManager->clear();
        }
    }
}
