<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Model\DataProcessor;

use Genaker\Bundle\DataGridBundle\Model\DataProcessor\StatusProcessor;
use PHPUnit\Framework\TestCase;

class StatusProcessorTest extends TestCase
{
    private StatusProcessor $processor;

    #[\Override]
    protected function setUp(): void
    {
        $this->processor = new StatusProcessor();
    }

    public function testProcessReturnsString(): void
    {
        self::assertSame('enabled', $this->processor->process('status', 'enabled', []));
    }

    public function testProcessObjectWithGetId(): void
    {
        $obj = new class {
            public function getId(): string
            {
                return 'in_stock';
            }
        };
        self::assertSame('in_stock', $this->processor->process('status', $obj, []));
    }

    public function testProcessReturnsEmptyForNull(): void
    {
        self::assertSame('', $this->processor->process('field', null, []));
    }
}
