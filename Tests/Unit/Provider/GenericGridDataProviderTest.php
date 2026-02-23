<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Provider;

use Genaker\Bundle\DataGridBundle\Builder\GridConfig;
use Genaker\Bundle\DataGridBundle\Provider\GenericGridDataProvider;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\ProductBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class GenericGridDataProviderTest extends TestCase
{
    public function testGetJsonGridDataReturnsStructure(): void
    {
        $config = new GridConfig(Product::class, ['id' => 'ID', 'sku' => 'SKU'], [], ['id', 'asc']);

        $product = new Product();
        $product->setSku('SKU-001');
        $ref = new \ReflectionClass(Product::class);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($product, 1);

        $dataQuery = $this->createMock(AbstractQuery::class);
        $dataQuery->method('getResult')->willReturn([$product]);

        $countQuery = $this->createMock(AbstractQuery::class);
        $countQuery->method('getSingleScalarResult')->willReturn(1);

        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('select')->willReturnSelf();
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('orderBy')->willReturnSelf();
        $qb->method('setFirstResult')->willReturnSelf();
        $qb->method('setMaxResults')->willReturnSelf();
        $qb->method('resetDQLPart')->willReturnSelf();
        $qb->method('getQuery')->willReturnCallback(function () use ($countQuery, $dataQuery) {
            static $callCount = 0;
            return (++$callCount === 1) ? $countQuery : $dataQuery;
        });

        $repo = $this->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();
        $repo->method('createQueryBuilder')->willReturn($qb);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(Product::class)->willReturn($repo);

        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->method('getManagerForClass')->with(Product::class)->willReturn($em);

        $provider = new GenericGridDataProvider($doctrine, $config);
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
        self::assertSame(1, $result['total']);
    }
}
