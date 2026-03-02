<?php

declare(strict_types=1);

use App\Seed\InventorySeed;
use App\Seed\RoleSeed;
use App\Seed\SeedProfile;
use App\Seed\SeedRunner;
use App\Seed\UserSeed;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';

$argv = $_SERVER['argv'] ?? [];
$profile = SeedProfile::DEV;
$force = false;
$verbose = false;

foreach ($argv as $argument) {
    if (str_starts_with($argument, '--profile=')) {
        $profile = (string) substr($argument, strlen('--profile='));
        continue;
    }

    if ($argument === '--force') {
        $force = true;
    }

    if ($argument === '--verbose' || $argument === '-v') {
        $verbose = true;
    }
}

$environment = (string) (
    $_ENV['APP_ENV']
    ?? $_SERVER['APP_ENV']
    ?? getenv('APP_ENV')
    ?? 'development'
);

if (in_array(strtolower($environment), ['prod', 'production'], true) && ! $force) {
    fwrite(
        STDERR,
        "Refusing to seed in production environment without --force.\n"
    );
    exit(1);
}

try {
    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    /** @var EntityManagerInterface $entityManager */
    $entityManager = $container->get(EntityManagerInterface::class);

    $runner = new SeedRunner([
        new RoleSeed(),
        new UserSeed(),
        new InventorySeed(),
    ]);

    $profile = SeedProfile::normalize($profile);
    $runner->run($entityManager, $profile);
    fwrite(STDOUT, sprintf("Seeding completed with profile: %s\n", $profile));
    exit(0);
} catch (\Throwable $exception) {
    fwrite(
        STDERR,
        sprintf(
            "Seeding failed (%s): %s\n",
            $exception::class,
            $exception->getMessage()
        )
    );
    if (str_contains($exception->getMessage(), 'SQLSTATE[HY000] [2002]')) {
        fwrite(
            STDERR,
            "Database connection failed. Check DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD and DATABASE_NAME in backend/.env.\n"
        );
    }
    if ($verbose) {
        fwrite(STDERR, $exception->getTraceAsString() . "\n");
    } else {
        fwrite(STDERR, "Re-run with --verbose for stack trace.\n");
    }
    exit(1);
}
