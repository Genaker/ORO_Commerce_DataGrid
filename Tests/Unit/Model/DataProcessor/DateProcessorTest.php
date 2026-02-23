<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Model\DataProcessor;

use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DateProcessor;
use PHPUnit\Framework\TestCase;

class DateProcessorTest extends TestCase
{
    public function testProcessFormatsDateTime(): void
    {
        $processor = new DateProcessor();
        $date = new \DateTime('2024-01-15 10:30:00');
        self::assertSame('2024-01-15', $processor->process('createdAt', $date, []));
    }

    public function testProcessCustomFormat(): void
    {
        $processor = new DateProcessor('Y-m-d H:i');
        $date = new \DateTime('2024-01-15 10:30:00');
        self::assertSame('2024-01-15 10:30', $processor->process('createdAt', $date, []));
    }

    public function testProcessReturnsEmptyForNull(): void
    {
        $processor = new DateProcessor();
        self::assertSame('', $processor->process('field', null, []));
    }
}
