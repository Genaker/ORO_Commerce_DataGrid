<?php

namespace Genaker\Bundle\DataGridBundle\Tests\Unit\Provider;

use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Genaker\Bundle\DataGridBundle\Provider\SampleProductDataProvider;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\ProductBundle\Helper\ProductImageHelper;
use PHPUnit\Framework\TestCase;

class SampleProductDataProviderTest extends TestCase
{
    public function testImplementsInterface(): void
    {
        $doctrine = $this->createMock(ManagerRegistry::class);
        $attachmentManager = $this->createMock(AttachmentManager::class);
        $productImageHelper = $this->createMock(ProductImageHelper::class);
        $provider = new SampleProductDataProvider($doctrine, $attachmentManager, $productImageHelper);
        self::assertInstanceOf(GridDataProviderInterface::class, $provider);
    }
}
