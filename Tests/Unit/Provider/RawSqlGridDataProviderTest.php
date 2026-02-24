<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Provider;

use Genaker\Bundle\DataGridBundle\Provider\RawSqlGridDataProvider;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class RawSqlGridDataProviderTest extends TestCase
{
    public function testGetJsonGridDataReturnsStructure(): void
    {
        $dataStatement = $this->createMock(\Doctrine\DBAL\Driver\Statement::class);
        $dataStatement->method('fetchAll')->with(\PDO::FETCH_ASSOC)->willReturn([
            ['id' => 1, 'sku' => 'SKU-001', 'name' => 'Product 1'],
        ]);

        $countStatement = $this->createMock(\Doctrine\DBAL\Driver\Statement::class);
        $countStatement->method('fetchColumn')->willReturn('1');

        $dataQb = $this->createQueryBuilderMock($dataStatement);
        $countQb = $this->createQueryBuilderMock($countStatement, true);

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
        $dataStatement = $this->createMock(\Doctrine\DBAL\Driver\Statement::class);
        $dataStatement->method('fetchAll')->with(\PDO::FETCH_ASSOC)->willReturn([]);

        $countStatement = $this->createMock(\Doctrine\DBAL\Driver\Statement::class);
        $countStatement->method('fetchColumn')->willReturn('0');

        $where = new \Doctrine\DBAL\Query\Expression\CompositeExpression('AND');
        $dataQb = $this->createQueryBuilderMock($dataStatement);
        $dataQb->method('getQueryPart')->with('where')->willReturn($where);
        $dataQb->method('getParameters')->willReturn(['filter_sku' => '%test%']);
        $dataQb->method('getParameterTypes')->willReturn([]);

        $countQb = $this->createQueryBuilderMock($countStatement, true);

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

    public function testGetTotalCount(): void
    {
        $countStatement = $this->createMock(\Doctrine\DBAL\Driver\Statement::class);
        $countStatement->method('fetchColumn')->willReturn('42');

        $dataQb = $this->createQueryBuilderMock($countStatement);
        $countQb = $this->createQueryBuilderMock($countStatement, true);

        $connection = $this->createMock(Connection::class);
        $connection->method('createQueryBuilder')->willReturnOnConsecutiveCalls($dataQb, $countQb);

        $provider = new RawSqlGridDataProvider($connection, 'oro_product');
        self::assertSame(42, $provider->getTotalCount(['id' => 'ID', 'sku' => 'SKU'], []));
    }

    /**
     * @param \Doctrine\DBAL\Driver\Statement $statement
     */
    private function createQueryBuilderMock($statement, bool $forCount = false): \Doctrine\DBAL\Query\QueryBuilder
    {
        $expr = $this->createMock(\Doctrine\DBAL\Query\Expression\ExpressionBuilder::class);
        $expr->method('like')->willReturn('t.sku LIKE :filter_sku');

        $qb = $this->getMockBuilder(\Doctrine\DBAL\Query\QueryBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'from', 'expr', 'andWhere', 'setParameter', 'orderBy', 'setFirstResult', 'setMaxResults', 'execute', 'getQueryPart', 'getParameters', 'getParameterTypes', 'where', 'setParameters'])
            ->getMock();
        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('expr')->willReturn($expr);
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('orderBy')->willReturnSelf();
        $qb->method('setFirstResult')->willReturnSelf();
        $qb->method('setMaxResults')->willReturnSelf();
        $qb->method('execute')->willReturn($statement);
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
