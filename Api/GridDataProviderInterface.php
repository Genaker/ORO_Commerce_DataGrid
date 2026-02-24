<?php

namespace Genaker\Bundle\DataGridBundle\Api;

/**
 * Reusable contract for grid data providers.
 */
interface GridDataProviderInterface
{
    /**
     * @param array<string, string> $fields Field name => label
     * @param array<string, mixed>  $filters
     *
     * @return array{data: array<int, array<string, mixed>>, total: int, timeSql?: float, timeCount?: float}
     */
    public function getJsonGridData(
        array $fields,
        array $filters,
        int $page,
        int $pageSize,
        ?string $sortField,
        ?string $sortOrder
    ): array;

    /**
     * Get total record count for current filters (no pagination).
     *
     * @param array<string, string> $fields Field name => label
     * @param array<string, mixed>  $filters
     */
    public function getTotalCount(array $fields, array $filters): int;
}
