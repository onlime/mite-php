<?php

declare(strict_types=1);

namespace Gamez\Mite\Tests\Integration;

use Gamez\Mite\Api\ApiClient;
use Gamez\Mite\Tests\Support\VCRApiClientFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
abstract class IntegrationTestCase extends TestCase
{
    protected static ApiClient $apiClient;

    /**
     * @var non-empty-string
     */
    protected static string $accountName;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $accountName = trim($_ENV['MITE_ACCOUNT'] ?? 'beste');
        $apiKey = trim($_ENV['MITE_API_KEY'] ?? '');

        if ($accountName === '') {
            self::markTestSkipped('Account name not provided');
        }

        self::$accountName = $accountName;

        $throwIfNotAbleToReplay = $apiKey === '';

        $apiClientFactory = new VCRApiClientFactory(__DIR__.'/../fixtures/vcr', $throwIfNotAbleToReplay);

        self::$apiClient = $apiClientFactory($accountName, $apiKey);
    }

    /**
     * @param list<non-empty-string> $expected
     * @param array<non-empty-string, mixed> $actual
     */
    protected function assertArrayStructure(array $expected, array $actual): void
    {
        Assert::assertEqualsCanonicalizing($expected, array_keys($actual));
    }

    /**
     * @param array<non-empty-string, mixed> $actual
     */
    protected function assertUserStructure(array $actual): void
    {
        $this->assertArrayStructure(
            [
                'id',
                'name',
                'email',
                'note',
                'archived',
                'role',
                'language',
                'created_at',
                'updated_at',
            ],
            $actual,
        );
    }

    /**
     * @param array<non-empty-string, mixed> $actual
     */
    protected function assertCustomerStructure(array $actual): void
    {
        $this->assertArrayStructure(
            [
                'id',
                'name',
                'note',
                'active_hourly_rate',
                'hourly_rate',
                'archived',
                'hourly_rates_per_service',
                'created_at',
                'updated_at',
            ],
            $actual,
        );

        self::assertIsList($actual['hourly_rates_per_service']);
    }
}
