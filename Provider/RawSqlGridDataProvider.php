<?php

namespace Genaker\Bundle\DataGridBundle\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as DbalQueryBuilder;
use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;

/**
 * Reusable provider for raw SQL queries. Uses Doctrine DBAL.
 */
class RawSqlGridDataProvider implements GridDataProviderInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $tableName,
        private readonly ?string $defaultSortField = null,
        private readonly string $defaultSortOrder = 'asc',
    ) {
    }

    #[\Override]
    public function getJsonGridData(
        array $fields,
        array $filters,
        int $page,
        int $pageSize,
        ?string $sortField,
        ?string $sortOrder
    ): array {
        $fieldNames = array_keys($fields);
        $selectCols = array_map(fn (string $f) => 't.' . $f, $fieldNames);
        $qb = $this->connection->createQueryBuilder();
        $qb->select($selectCols)->from($this->tableName, 't');

        $this->applyFilters($qb, $filters, $fieldNames);
        $total = $this->getCount($qb);

        $sortField = $sortField ?? $this->defaultSortField;
        $sortOrder = $sortOrder ?? $this->defaultSortOrder;
        if ($sortField !== null && \in_array($sortField, $fieldNames, true)) {
            $qb->orderBy('t.' . $sortField, strtoupper($sortOrder));
        }

        $qb->setFirstResult(($page - 1) * $pageSize);
        $qb->setMaxResults($pageSize);

        $startSql = microtime(true);
        $results = $qb->executeQuery()->fetchAllAssociative();
        $timeSql = microtime(true) - $startSql;

        return [
            'data' => $results,
            'total' => $total,
            'timeSql' => round($timeSql, 4),
        ];
    }

    /**
     * @param list<string> $allowedFields
     */
    private function applyFilters(DbalQueryBuilder $qb, array $filters, array $allowedFields): void
    {
        foreach ($filters as $field => $value) {
            if ($value === null || $value === '' || !\in_array($field, $allowedFields, true)) {
                continue;
            }
            $param = 'filter_' . $field;
            $qb->andWhere($qb->expr()->like('t.' . $field, ':' . $param));
            $qb->setParameter($param, '%' . $value . '%');
        }
    }

    private function getCount(DbalQueryBuilder $qb): int
    {
        $countQb = $this->connection->createQueryBuilder();
        $countQb->select('COUNT(*)')->from($this->tableName, 't');
        $where = $qb->getQueryPart('where');
        if ($where !== null) {
            $countQb->where($where);
        }
        $countQb->setParameters($qb->getParameters(), $qb->getParameterTypes());

        return (int) $countQb->executeQuery()->fetchOne();
    }
}
