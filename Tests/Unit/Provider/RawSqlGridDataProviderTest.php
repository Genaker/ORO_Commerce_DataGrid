<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Provider;

use Genaker\Bundle\DataGridBundle\Provider\RawSqlGridDataProvider;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\TestCase;

class RawSqlGridDataProviderTest extends TestCase
{
    public function testGetJsonGridDataReturnsStructure(): void
    {
        $dataResult = $this->createMock(Result::class);
        $dataResult->method('fetchAllAssociative')->willReturn([
            ['id' => 1, 'sku' => 'SKU-001', 'name' => 'Product 1'],
        ]);

        $countResult = $this->createMock(Result::class);
        $countResult->method('fetchOne')->willReturn('1');

        $dataQb = $this->createQueryBuilderMock($dataResult);
        $countQb = $this->createQueryBuilderMock($countResult, true);

        $connection = $this->createMock(Connection::class);
        $connection->method('createQueryBuilder')->willReturnOnConsecutiveCalls($dataQb, $countQb);

        $provider = new RawSqlGridDataProvider($connection, 'oro_product', 'id', 'asc');
        $output = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU', 'name' => 'Name'],
            [],
            1,
            20,
            null,
            null
        );

        self::assertArrayHasKey('data', $output);
        self::assertArrayHasKey('total', $output);
        self::assertArrayHasKey('timeSql', $output);
        self::assertIsArray($output['data']);
        self::assertSame(1, $output['total']);
    }

    public function testGetJsonGridDataWithFilters(): void
    {
        $dataResult = $this->createMock(Result::class);
        $dataResult->method('fetchAllAssociative')->willReturn([]);
        $countResult = $this->createMock(Result::class);
        $countResult->method('fetchOne')->willReturn('0');

        $where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND');
        $dataQb = $this->createQueryBuilderMock($dataResult);
        $dataQb->method('getQueryPart')->with('where')->willReturn($where);
        $dataQb->method('getParameters')->willReturn(['filter_sku' => '%test%']);
        $dataQb->method('getParameterTypes')->willReturn([]);

        $countQb = $this->createQueryBuilderMock($countResult, true);

        $connection = $this->createMock(Connection::class);
        $connection->method('createQueryBuilder')->willReturnOnConsecutiveCalls($dataQb, $countQb);

        $provider = new RawSqlGridDataProvider($connection, 'oro_product');
        $output = $provider->getJsonGridData(
            ['id' => 'ID', 'sku' => 'SKU'],
            ['sku' => 'test'],
            1,
            20,
            'sku',
            'asc'
        );

        self::assertArrayHasKey('data', $output);
        self::assertArrayHasKey('total', $output);
        self::assertSame(0, $output['total']);
    }

    private function createQueryBuilderMock(Result $result, bool $forCount = false): \Doctrine\DBAL\Query\QueryBuilder
    {
        $expr = $this->createMock(\Doctrine\DBAL\Query\Expression\ExpressionBuilder::class);
        $expr->method('like')->willReturn('t.sku LIKE :filter_sku');

        $qb = $this->getMockBuilder(\Doctrine\DBAL\Query\QueryBuilder::class)
            ->disableOriginalConstructor()
            ->addMethods(['executeQuery'])
            ->onlyMethods(['select', 'from', 'expr', 'andWhere', 'setParameter', 'orderBy', 'setFirstResult', 'setMaxResults', 'getQueryPart', 'getParameters', 'getParameterTypes', 'where', 'setParameters'])
            ->getMock();
        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('expr')->willReturn($expr);
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('orderBy')->willReturnSelf();
        $qb->method('setFirstResult')->willReturnSelf();
        $qb->method('setMaxResults')->willReturnSelf();
        $qb->method('executeQuery')->willReturn($result);
        $qb->method('getQueryPart')->with('where')->willReturn(null);
        $qb->method('getParameters')->willReturn([]);
        $qb->method('getParameterTypes')->willReturn([]);
        if ($forCount) {
            $qb->method('where')->willReturnSelf();
            $qb->method('setParameters')->willReturnSelf();
        }
        return $qb;
    }
}
