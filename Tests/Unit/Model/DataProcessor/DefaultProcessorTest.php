<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Model\DataProcessor;

use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DefaultProcessor;
use PHPUnit\Framework\TestCase;

class DefaultProcessorTest extends TestCase
{
    private DefaultProcessor $processor;

    #[\Override]
    protected function setUp(): void
    {
        $this->processor = new DefaultProcessor();
    }

    public function testProcessReturnsValue(): void
    {
        self::assertSame('foo', $this->processor->process('field', 'foo', []));
        self::assertSame(123, $this->processor->process('id', 123, []));
    }

    public function testProcessReturnsEmptyStringForNull(): void
    {
        self::assertSame('', $this->processor->process('field', null, []));
    }
}
