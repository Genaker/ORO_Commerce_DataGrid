<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Integration\Provider;

use Genaker\Bundle\DataGridBundle\Provider\SampleProductDataProvider;
use Genaker\Bundle\DataGridBundle\Tests\Integration\DataGridIntegrationTestCase;

class SampleProductDataProviderIntegrationTest extends DataGridIntegrationTestCase
{
    public function testGetJsonGridDataReturnsStructure(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product');
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
        self::assertIsArray($result['data']);
        self::assertIsInt($result['total']);
    }

    public function testGetJsonGridDataWithPagination(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product');
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
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product');
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
    }

    public function testGetJsonGridDataWithCustomFields(): void
    {
        $provider = static::getContainer()->get('genaker_data_grid.provider.sample_product');
        $result = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU', 'denormalizedDefaultName' => 'Name'],
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
