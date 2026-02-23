<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Builder;

use Genaker\Bundle\DataGridBundle\Builder\GridBuilder;
use Genaker\Bundle\DataGridBundle\Builder\GridConfig;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DateProcessor;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\StatusProcessor;
use Oro\Bundle\ProductBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class GridBuilderTest extends TestCase
{
    private \Doctrine\Persistence\ManagerRegistry $doctrine;

    #[\Override]
    protected function setUp(): void
    {
        $this->doctrine = $this->createMock(\Doctrine\Persistence\ManagerRegistry::class);
    }

    public function testBuildProductConfig(): void
    {
        $config = (new GridBuilder($this->doctrine))
            ->setEntity(Product::class)
            ->setFields(['id' => 'ID', 'sku' => 'SKU', 'status' => 'Status'])
            ->addProcessor('createdAt', new DateProcessor())
            ->addProcessor('status', new StatusProcessor())
            ->setDefaultSort('sku', 'asc')
            ->build();

        self::assertInstanceOf(GridConfig::class, $config);
        self::assertSame(Product::class, $config->getEntityClass());
        self::assertSame(['id' => 'ID', 'sku' => 'SKU', 'status' => 'Status'], $config->getFields());
        self::assertCount(2, $config->getProcessors());
        self::assertSame(['sku', 'asc'], $config->getDefaultSort());
    }

    public function testBuildOrderConfigReusability(): void
    {
        $orderClass = 'Oro\Bundle\OrderBundle\Entity\Order';
        if (!class_exists($orderClass)) {
            self::markTestSkipped('Order entity not available');
        }

        $config = (new GridBuilder($this->doctrine))
            ->setEntity($orderClass)
            ->setFields(['id' => 'ID', 'identifier' => 'Order #', 'status' => 'Status'])
            ->setDefaultSort('id', 'desc')
            ->build();

        self::assertInstanceOf(GridConfig::class, $config);
        self::assertSame($orderClass, $config->getEntityClass());
        self::assertSame(['id', 'desc'], $config->getDefaultSort());
    }

    public function testBuildThrowsWithoutEntity(): void
    {
        $this->expectException(\LogicException::class);
        (new GridBuilder($this->doctrine))
            ->setFields(['id' => 'ID'])
            ->build();
    }

    public function testBuildThrowsWithoutFields(): void
    {
        $this->expectException(\LogicException::class);
        (new GridBuilder($this->doctrine))
            ->setEntity(Product::class)
            ->build();
    }
}
