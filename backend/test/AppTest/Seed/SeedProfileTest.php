<?php

declare(strict_types=1);

namespace AppTest\Seed;

use App\Seed\SeedProfile;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SeedProfileTest extends TestCase
{
    public function testNormalizeAcceptsKnownProfiles(): void
    {
        self::assertSame(SeedProfile::MINIMAL, SeedProfile::normalize('minimal'));
        self::assertSame(SeedProfile::DEV, SeedProfile::normalize('DEV'));
        self::assertSame(SeedProfile::DEMO, SeedProfile::normalize(' demo '));
    }

    public function testNormalizeRejectsUnknownProfile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SeedProfile::normalize('unknown');
    }

    public function testIncludesWorksByProfileOrder(): void
    {
        self::assertTrue(SeedProfile::includes(SeedProfile::DEMO, SeedProfile::DEV));
        self::assertTrue(SeedProfile::includes(SeedProfile::DEV, SeedProfile::MINIMAL));
        self::assertFalse(SeedProfile::includes(SeedProfile::MINIMAL, SeedProfile::DEMO));
    }
}
