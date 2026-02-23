<?php

namespace Genaker\Bundle\DataGridBundle\Builder;

use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DataProcessorInterface;

/**
 * DTO for grid configuration. Entity-agnostic.
 */
final class GridConfig
{
    /**
     * @param class-string $entityClass
     * @param array<string, string> $fields Field name => label
     * @param array<string, DataProcessorInterface> $processors Field name => processor
     * @param array{0: string, 1: string}|null $defaultSort [field, asc|desc]
     */
    public function __construct(
        private readonly string $entityClass,
        private readonly array $fields,
        private readonly array $processors = [],
        private readonly ?array $defaultSort = null,
    ) {
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @return array<string, string>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array<string, DataProcessorInterface>
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * @return array{0: string, 1: string}|null
     */
    public function getDefaultSort(): ?array
    {
        return $this->defaultSort;
    }

    /**
     * @return list<string>
     */
    public function getFieldNames(): array
    {
        return array_keys($this->fields);
    }
}
