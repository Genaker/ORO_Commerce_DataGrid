<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Block;

use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Genaker\Bundle\DataGridBundle\Block\DataGridBlock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DataGridBlockTest extends TestCase
{
    public function testGetFieldsReturnsProductFields(): void
    {
        $provider = $this->createMock(GridDataProviderInterface::class);
        $requestStack = new RequestStack();
        $router = $this->createMock(UrlGeneratorInterface::class);
        $router->method('generate')->willReturn('/grid/gridjs/data');

        $block = new DataGridBlock($provider, $requestStack, $router, 'genaker_data_grid_gridjs_data');

        self::assertArrayHasKey('id', $block->getFields());
        self::assertArrayHasKey('sku', $block->getFields());
        self::assertArrayHasKey('denormalizedDefaultName', $block->getFields());
    }
}
