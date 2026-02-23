<?php

namespace Genaker\Bundle\DataGridBundle\Provider;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Genaker\Bundle\DataGridBundle\Builder\GridConfig;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DataProcessorInterface;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DefaultProcessor;

/**
 * Reusable provider. Works with any entity via GridConfig.
 */
class GenericGridDataProvider implements GridDataProviderInterface
{
    private const ALIAS = 'e';

    private readonly DefaultProcessor $defaultProcessor;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly GridConfig $config,
    ) {
        $this->defaultProcessor = new DefaultProcessor();
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
        $qb = $this->createQueryBuilder($fieldNames);
        $this->applyFilters($qb, $filters, $fieldNames);
        $total = $this->getCount($qb);

        $sortField = $sortField ?? $this->config->getDefaultSort()[0] ?? null;
        $sortOrder = $sortOrder ?? $this->config->getDefaultSort()[1] ?? 'asc';
        if ($sortField !== null && \in_array($sortField, $fieldNames, true)) {
            $this->applySort($qb, $sortField, $sortOrder);
        }

        $qb->setFirstResult(($page - 1) * $pageSize);
        $qb->setMaxResults($pageSize);

        $startSql = microtime(true);
        $results = $qb->getQuery()->getResult();
        $timeSql = microtime(true) - $startSql;

        $data = $this->formatRows($results, $fieldNames);

        return [
            'data' => $data,
            'total' => $total,
            'timeSql' => round($timeSql, 4),
        ];
    }

    /**
     * @param list<string> $fieldNames
     */
    private function createQueryBuilder(array $fieldNames): QueryBuilder
    {
        $em = $this->doctrine->getManagerForClass($this->config->getEntityClass());
        $qb = $em->getRepository($this->config->getEntityClass())
            ->createQueryBuilder(self::ALIAS);

        $qb->select(self::ALIAS);

        return $qb;
    }

    /**
     * @param list<string> $allowedFields
     */
    private function applyFilters(QueryBuilder $qb, array $filters, array $allowedFields): void
    {
        foreach ($filters as $field => $value) {
            if ($value === null || $value === '' || !\in_array($field, $allowedFields, true)) {
                continue;
            }
            $param = 'filter_' . $field;
            $qb->andWhere($qb->expr()->like(self::ALIAS . '.' . $field, ':' . $param))
                ->setParameter($param, '%' . $value . '%');
        }
    }

    private function applySort(QueryBuilder $qb, string $field, string $order): void
    {
        $qb->orderBy(self::ALIAS . '.' . $field, strtoupper($order));
    }

    private function getCount(QueryBuilder $qb): int
    {
        $countQb = clone $qb;
        $countQb->select('COUNT(' . self::ALIAS . ')');
        $countQb->setFirstResult(null);
        $countQb->setMaxResults(null);
        $countQb->resetDQLPart('orderBy');

        return (int) $countQb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param list<string> $fieldNames
     * @return array<int, array<string, mixed>>
     */
    private function formatRows(array $results, array $fieldNames): array
    {
        $processors = $this->config->getProcessors();
        $data = [];

        foreach ($results as $row) {
            $arr = \is_array($row) ? $row : $this->entityToArray($row, $fieldNames);
            if (\is_object($row)) {
                $arr['entity'] = $row;
            }
            $out = [];
            foreach ($fieldNames as $f) {
                $value = $arr[$f] ?? null;
                $processor = $processors[$f] ?? $this->defaultProcessor;
                $out[$f] = $processor->process($f, $value, $arr);
            }
            $data[] = $out;
        }

        return $data;
    }

    /**
     * @param object $entity
     * @param list<string> $fieldNames
     * @return array<string, mixed>
     */
    private function entityToArray(object $entity, array $fieldNames): array
    {
        $arr = [];
        foreach ($fieldNames as $f) {
            $getter = 'get' . ucfirst($f);
            if (method_exists($entity, $getter)) {
                $arr[$f] = $entity->$getter();
            }
        }
        return $arr;
    }
}
