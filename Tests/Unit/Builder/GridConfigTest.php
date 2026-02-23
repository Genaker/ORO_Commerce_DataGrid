<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Builder;

use Genaker\Bundle\DataGridBundle\Builder\GridConfig;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DefaultProcessor;
use PHPUnit\Framework\TestCase;

class GridConfigTest extends TestCase
{
    public function testGetters(): void
    {
        $processor = new DefaultProcessor();
        $config = new GridConfig(
            'App\Entity\Product',
            ['id' => 'ID', 'sku' => 'SKU'],
            ['sku' => $processor],
            ['id', 'asc']
        );

        self::assertSame('App\Entity\Product', $config->getEntityClass());
        self::assertSame(['id' => 'ID', 'sku' => 'SKU'], $config->getFields());
        self::assertSame(['sku' => $processor], $config->getProcessors());
        self::assertSame(['id', 'asc'], $config->getDefaultSort());
        self::assertSame(['id', 'sku'], $config->getFieldNames());
    }

    public function testEntityAgnostic(): void
    {
        $config = new GridConfig('App\Entity\Order', ['number' => 'Order #'], [], null);
        self::assertSame('App\Entity\Order', $config->getEntityClass());
        self::assertSame(['number'], $config->getFieldNames());
    }
}
