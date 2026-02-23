<?php

namespace Genaker\Bundle\DataGridBundle\Model\DataProcessor;

/**
 * Pass-through processor. Returns value as-is or empty string for null.
 */
class DefaultProcessor implements DataProcessorInterface
{
    #[\Override]
    public function process(string $field, mixed $value, array $row): mixed
    {
        return $value ?? '';
    }
}
