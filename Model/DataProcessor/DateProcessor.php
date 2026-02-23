<?php

namespace Genaker\Bundle\DataGridBundle\Model\DataProcessor;

/**
 * Formats DateTime values for display.
 */
class DateProcessor implements DataProcessorInterface
{
    public function __construct(
        private readonly string $format = 'Y-m-d',
    ) {
    }

    #[\Override]
    public function process(string $field, mixed $value, array $row): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format($this->format);
        }
        return $value ?? '';
    }
}
