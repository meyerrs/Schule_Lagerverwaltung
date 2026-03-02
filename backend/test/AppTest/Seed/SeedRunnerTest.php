<?php

declare(strict_types=1);

namespace AppTest\Seed;

use App\Seed\SeedInterface;
use App\Seed\SeedProfile;
use App\Seed\SeedRunner;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class SeedRunnerTest extends TestCase
{
    public function testRunnerExecutesAllSeedsAndFlushesBetweenThem(): void
    {
        $executed = [];

        $seedA = new class($executed) implements SeedInterface {
            /**
             * @param array<int, string> $executed
             */
            public function __construct(private array &$executed)
            {
            }

            public function name(): string
            {
                return 'a';
            }

            public function run(EntityManagerInterface $entityManager, string $profile): void
            {
                $this->executed[] = 'a:' . $profile;
            }
        };

        $seedB = new class($executed) implements SeedInterface {
            /**
             * @param array<int, string> $executed
             */
            public function __construct(private array &$executed)
            {
            }

            public function name(): string
            {
                return 'b';
            }

            public function run(EntityManagerInterface $entityManager, string $profile): void
            {
                $this->executed[] = 'b:' . $profile;
            }
        };

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects(self::exactly(2))
            ->method('flush');
        $entityManager
            ->expects(self::exactly(2))
            ->method('clear');

        $runner = new SeedRunner([$seedA, $seedB]);
        $runner->run($entityManager, SeedProfile::DEV);

        self::assertSame(['a:dev', 'b:dev'], $executed);
    }
}
