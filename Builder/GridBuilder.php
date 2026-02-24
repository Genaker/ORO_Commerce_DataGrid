<?php

namespace Genaker\Bundle\DataGridBundle\Builder;

use Doctrine\Persistence\ManagerRegistry;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DataProcessorInterface;

/**
 * Fluent builder for grid config. Reusable for any entity (Product, Order, etc.).
 */
class GridBuilder
{
    /** @var class-string|null */
    private ?string $entityClass = null;

    /** @var array<string, string> */
    private array $fields = [];

    /** @var array<string, DataProcessorInterface> */
    private array $processors = [];

    /** @var array{0: string, 1: string}|null */
    private ?array $defaultSort = null;

    /** @var list<array{0: string, 1: string}> */
    private array $joins = [];

    public function __construct(
        private readonly ManagerRegistry $doctrine,
    ) {
    }

    /**
     * @param class-string $entityClass
     */
    public function setEntity(string $entityClass): self
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @param array<string, string> $fields Field name => label
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function addProcessor(string $field, DataProcessorInterface $processor): self
    {
        $this->processors[$field] = $processor;
        return $this;
    }

    /**
     * @param 'asc'|'desc' $order
     */
    public function setDefaultSort(string $field, string $order = 'asc'): self
    {
        $this->defaultSort = [$field, strtolower($order) === 'desc' ? 'desc' : 'asc'];
        return $this;
    }

    /**
     * @param string $join e.g. 'e.images'
     * @param string $alias e.g. 'pi'
     */
    public function addJoin(string $join, string $alias): self
    {
        $this->joins[] = [$join, $alias];
        return $this;
    }

    public function build(): GridConfig
    {
        if ($this->entityClass === null || $this->fields === []) {
            throw new \LogicException('Entity and fields must be set before build().');
        }
        return new GridConfig(
            $this->entityClass,
            $this->fields,
            $this->processors,
            $this->defaultSort,
            $this->joins,
        );
    }
}
