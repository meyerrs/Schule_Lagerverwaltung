<?php

declare(strict_types=1);

namespace App\Seed;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;

final class RoleSeed implements SeedInterface
{
    public function name(): string
    {
        return 'roles';
    }

    public function run(EntityManagerInterface $entityManager, string $profile): void
    {
        $roleRepository = $entityManager->getRepository(Role::class);

        $roleNames = ['admin', 'teacher'];
        if (SeedProfile::includes($profile, SeedProfile::DEV)) {
            $roleNames[] = 'student';
        }
        if (SeedProfile::includes($profile, SeedProfile::DEMO)) {
            $roleNames[] = 'viewer';
        }

        foreach ($roleNames as $roleName) {
            $role = $roleRepository->findOneBy(['name' => $roleName]);
            if ($role instanceof Role) {
                continue;
            }

            $role = new Role();
            $role->setName($roleName);
            $entityManager->persist($role);
        }
    }
}
