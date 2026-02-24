<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration\Provider;

use Genaker\Bundle\DataGridBundle\Provider\SampleProductSqlDataProvider;
use Genaker\Bundle\DataGridBundle\Tests\Integration\DataGridIntegrationTestCase;

class SampleProductSqlDataProviderIntegrationTest extends DataGridIntegrationTestCase
{
    public function testGetJsonGridDataReturnsStructure(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product_sql');
        $result = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU'],
            [],
            1,
            20,
            null,
            null
        );

        self::assertArrayHasKey('data', $result);
        self::assertArrayHasKey('total', $result);
        self::assertArrayHasKey('timeSql', $result);
        self::assertIsArray($result['data']);
        self::assertIsInt($result['total']);
        self::assertIsFloat($result['timeSql']);
    }

    public function testGetJsonGridDataWithPagination(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product_sql');
        $result = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU'],
            [],
            2,
            5,
            null,
            null
        );

        self::assertArrayHasKey('data', $result);
        self::assertArrayHasKey('total', $result);
        self::assertIsArray($result['data']);
        self::assertLessThanOrEqual(5, \count($result['data']));
        self::assertGreaterThanOrEqual(0, $result['total']);
    }

    public function testGetJsonGridDataWithSorting(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product_sql');
        $result = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU'],
            [],
            1,
            10,
            'sku',
            'desc'
        );

        self::assertArrayHasKey('data', $result);
        self::assertArrayHasKey('total', $result);
        self::assertIsArray($result['data']);
        if (\count($result['data']) > 1) {
            $firstSku = $result['data'][0]['sku'] ?? null;
            $secondSku = $result['data'][1]['sku'] ?? null;
            if ($firstSku !== null && $secondSku !== null) {
                self::assertGreaterThanOrEqual($secondSku, $firstSku, 'SKUs should be sorted descending');
            }
        }
    }

    public function testGetJsonGridDataWithFilters(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product_sql');
        $result = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU'],
            ['sku' => 'test'],
            1,
            20,
            null,
            null
        );

        self::assertArrayHasKey('data', $result);
        self::assertArrayHasKey('total', $result);
        self::assertIsArray($result['data']);
        foreach ($result['data'] as $row) {
            if (isset($row['sku'])) {
                self::assertStringContainsStringIgnoringCase('test', $row['sku'], 'Filtered SKU should contain "test"');
            }
        }
    }

    public function testGetJsonGridDataWithCustomFields(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product_sql');
        $result = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU', 'status' => 'Status'],
            [],
            1,
            3,
            null,
            null
        );

        self::assertArrayHasKey('data', $result);
        self::assertArrayHasKey('total', $result);
        self::assertIsArray($result['data']);
        if (\count($result['data']) > 0) {
            self::assertArrayHasKey('id', $result['data'][0]);
            self::assertArrayHasKey('sku', $result['data'][0]);
        }
    }
}
