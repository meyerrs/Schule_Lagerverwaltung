<?php

declare(strict_types=1);

namespace App\Seed;

final class SeedProfile
{
    public const MINIMAL = 'minimal';
    public const DEV = 'dev';
    public const DEMO = 'demo';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::MINIMAL,
            self::DEV,
            self::DEMO,
        ];
    }

    public static function normalize(string $profile): string
    {
        $normalized = strtolower(trim($profile));
        if (! in_array($normalized, self::all(), true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unknown seed profile "%s". Allowed values: %s',
                    $profile,
                    implode(', ', self::all())
                )
            );
        }

        return $normalized;
    }

    public static function includes(string $selectedProfile, string $requiredProfile): bool
    {
        $order = [
            self::MINIMAL => 1,
            self::DEV => 2,
            self::DEMO => 3,
        ];

        return $order[$selectedProfile] >= $order[$requiredProfile];
    }
}
