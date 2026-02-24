<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Block;

use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Genaker\Bundle\DataGridBundle\Block\GenericGridBlock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class GenericGridBlockTest extends TestCase
{
    public function testGetFieldsReturnsDefaultFields(): void
    {
        $provider = $this->createMock(GridDataProviderInterface::class);
        $requestStack = new RequestStack();
        $block = new GenericGridBlock($provider, $requestStack, ['id' => 'ID', 'sku' => 'SKU'], '');

        self::assertSame(['id' => 'ID', 'sku' => 'SKU'], $block->getFields());
        self::assertSame(['id', 'sku'], $block->getFieldsNames());
    }

    public function testGetDataUrl(): void
    {
        $provider = $this->createMock(GridDataProviderInterface::class);
        $requestStack = new RequestStack();
        $block = new GenericGridBlock($provider, $requestStack, [], 'https://example.com/data');

        self::assertSame('https://example.com/data', $block->getDataUrl());
    }

    public function testGetGridJsonDataCallsProvider(): void
    {
        $provider = $this->createMock(GridDataProviderInterface::class);
        $provider->expects(self::once())
            ->method('getJsonGridData')
            ->with(['id' => 'ID'], [], 1, 10, null, null)
            ->willReturn(['data' => [], 'total' => 0]);

        $request = new Request();
        $request->query->set('p', '1');
        $request->query->set('pageSize', '10');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $block = new GenericGridBlock($provider, $requestStack, ['id' => 'ID'], '');
        $result = $block->getGridJsonData();

        self::assertSame(['data' => [], 'total' => 0, 'page' => 1, 'pageSize' => 10], $result);
    }

    public function testGetGridJsonDataUsesPageWhenPNotSet(): void
    {
        $provider = $this->createMock(GridDataProviderInterface::class);
        $provider->expects(self::once())
            ->method('getJsonGridData')
            ->with(['id' => 'ID'], [], 2, 10, null, null)
            ->willReturn(['data' => [], 'total' => 0]);

        $request = new Request();
        $request->query->set('page', '2');
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $block = new GenericGridBlock($provider, $requestStack, ['id' => 'ID'], '');
        $block->getGridJsonData();
    }

    public function testGetGridJsonDataPassesUrlParamsToDataProvider(): void
    {
        $provider = $this->createMock(GridDataProviderInterface::class);
        $provider->expects(self::once())
            ->method('getJsonGridData')
            ->with(
                ['id' => 'ID', 'sku' => 'SKU'],
                ['sku' => 'ABC'],
                2,
                25,
                'sku',
                'desc'
            )
            ->willReturn(['data' => [], 'total' => 0]);

        $request = new Request();
        $request->query->set('p', '2');
        $request->query->set('pageSize', '25');
        $request->query->set('sortField', 'sku');
        $request->query->set('sortOrder', 'desc');
        $request->query->set('filter', ['sku' => 'ABC']);
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $block = new GenericGridBlock($provider, $requestStack, ['id' => 'ID', 'sku' => 'SKU'], '');
        $block->getGridJsonData();
    }
}
