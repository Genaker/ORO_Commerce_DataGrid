<?php

namespace Genaker\Bundle\DataGridBundle\Model\DataProcessor;

/**
 * Formats status values (e.g. enabled/disabled).
 */
class StatusProcessor implements DataProcessorInterface
{
    #[\Override]
    public function process(string $field, mixed $value, array $row): mixed
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (\is_object($value) && method_exists($value, 'getId')) {
            return (string) $value->getId();
        }
        return (string) $value;
    }
}
