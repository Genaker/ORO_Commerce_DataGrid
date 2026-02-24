<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Controller;

use Genaker\Bundle\DataGridBundle\Block\GenericGridBlock;
use Genaker\Bundle\DataGridBundle\Controller\AgGridController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class AgGridControllerTest extends TestCase
{
    public function testDataActionReturnsJsonResponse(): void
    {
        $block = $this->createMock(GenericGridBlock::class);
        $block->method('getGridJsonData')->willReturn(['data' => [], 'total' => 0, 'page' => 1, 'pageSize' => 10]);

        $controller = new AgGridController($block);
        $response = $controller->data();

        self::assertInstanceOf(JsonResponse::class, $response);
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('paginationHtml', $content);
    }
}
