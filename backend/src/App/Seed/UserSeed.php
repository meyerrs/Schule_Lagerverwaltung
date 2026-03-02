<?php

declare(strict_types=1);

namespace App\Seed;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class UserSeed implements SeedInterface
{
    public function name(): string
    {
        return 'users';
    }

    public function run(EntityManagerInterface $entityManager, string $profile): void
    {
        $roleRepository = $entityManager->getRepository(Role::class);
        $userRepository = $entityManager->getRepository(User::class);

        $users = [
            [
                'username' => 'admin',
                'firstname' => 'System',
                'lastname' => 'Admin',
                'password' => 'admin123',
                'roles' => ['admin'],
            ],
            [
                'username' => 'lehrer.demo',
                'firstname' => 'Max',
                'lastname' => 'Lehrer',
                'password' => 'demo123',
                'roles' => ['teacher'],
            ],
        ];

        if (SeedProfile::includes($profile, SeedProfile::DEV)) {
            $users[] = [
                'username' => 'schueler.demo',
                'firstname' => 'Lena',
                'lastname' => 'Schueler',
                'password' => 'demo123',
                'roles' => ['student'],
            ];
        }

        if (SeedProfile::includes($profile, SeedProfile::DEMO)) {
            $users[] = [
                'username' => 'lager.viewer',
                'firstname' => 'Nina',
                'lastname' => 'Viewer',
                'password' => 'demo123',
                'roles' => ['viewer'],
            ];
        }

        foreach ($users as $seededUser) {
            $user = $userRepository->findOneBy(['username' => $seededUser['username']]);
            if (! $user instanceof User) {
                $user = new User();
                $user->setUsername($seededUser['username']);
                $entityManager->persist($user);
            }

            $user->setFirstname($seededUser['firstname']);
            $user->setLastname($seededUser['lastname']);
            $user->setPassword($seededUser['password']);

            foreach ($seededUser['roles'] as $roleName) {
                $role = $roleRepository->findOneBy(['name' => $roleName]);
                if (! $role instanceof Role) {
                    throw new \RuntimeException(sprintf('Role "%s" must exist before user seeding.', $roleName));
                }
                $user->addRole($role);
            }
        }
    }
}
