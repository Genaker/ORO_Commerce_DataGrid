<?php

namespace Genaker\Bundle\DataGridBundle\Model\DataProcessor;

/**
 * Field-agnostic cell formatter.
 */
interface DataProcessorInterface
{
    /**
     * @param mixed $value
     * @param array<string, mixed> $row
     */
    public function process(string $field, mixed $value, array $row): mixed;
}
