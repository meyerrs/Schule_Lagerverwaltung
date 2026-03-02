<?php

declare(strict_types=1);

namespace App\Seed;

use Doctrine\ORM\EntityManagerInterface;

interface SeedInterface
{
    public function name(): string;

    public function run(EntityManagerInterface $entityManager, string $profile): void;
}
