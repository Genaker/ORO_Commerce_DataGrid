<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Controller;

use Genaker\Bundle\DataGridBundle\Block\GenericGridBlock;
use Genaker\Bundle\DataGridBundle\Controller\GridJsController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GridJsControllerTest extends TestCase
{
    public function testDataActionReturnsJsonResponse(): void
    {
        $block = $this->createMock(GenericGridBlock::class);
        $block->method('getGridJsonData')->willReturn(['data' => [], 'total' => 0]);

        $controller = new GridJsController($block);
        $response = $controller->data();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame('{"data":[],"total":0}', $response->getContent());
    }
}
