<?php

namespace Genaker\Bundle\DataGridBundle\Block;

use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Reusable block. Works with any GridDataProviderInterface.
 */
class GenericGridBlock
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_PAGE_SIZE = 20;

    /**
     * @param array<string, string> $defaultFields Field name => label
     */
    public function __construct(
        private readonly GridDataProviderInterface $dataProvider,
        private readonly RequestStack $requestStack,
        private readonly array $defaultFields = [],
        private readonly string $dataUrl = '',
    ) {
    }

    /**
     * @return array<string, string> Field name => label
     */
    public function getFields(): array
    {
        return $this->getFieldsFromRequest() ?: $this->defaultFields;
    }

    /**
     * @return list<string>
     */
    public function getFieldsNames(): array
    {
        return array_keys($this->getFields());
    }

    /**
     * @return array{data: array, total: int, timeSql?: float, timeCount?: float}
     */
    public function getGridJsonData(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $fields = $this->getFields();
        $filters = $this->getFilterValues();
        $page = $request ? (int) $request->query->get('page', self::DEFAULT_PAGE) : self::DEFAULT_PAGE;
        $pageSize = $request ? (int) $request->query->get('pageSize', self::DEFAULT_PAGE_SIZE) : self::DEFAULT_PAGE_SIZE;
        $sortField = $request ? $request->query->get('sortField') : null;
        $sortOrder = $request ? $request->query->get('sortOrder') : null;

        return $this->dataProvider->getJsonGridData($fields, $filters, $page, $pageSize, $sortField, $sortOrder);
    }

    /**
     * @return array<string, mixed> Current filter values from request
     */
    public function getFilterValues(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return [];
        }
        $filter = $request->query->all('filter');
        return \is_array($filter) ? $filter : [];
    }

    public function getDataUrl(): string
    {
        if (empty($this->dataUrl)) {
            return '';
        }

        $filters = $this->getFilterValues();
        if (empty($filters)) {
            return $this->dataUrl;
        }

        $query = ['filter' => $filters];
        $sep = str_contains($this->dataUrl, '?') ? '&' : '?';

        return $this->dataUrl . $sep . http_build_query($query);
    }

    /**
     * @return array<string, string>|null
     */
    protected function getFieldsFromRequest(): ?array
    {
        return null;
    }
}
