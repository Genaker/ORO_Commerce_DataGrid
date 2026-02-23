<?php

namespace Genaker\Bundle\DataGridBundle\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Genaker\Bundle\DataGridBundle\Api\GridDataProviderInterface;
use Genaker\Bundle\DataGridBundle\Builder\GridBuilder;
use Genaker\Bundle\DataGridBundle\Builder\GridConfig;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DateProcessor;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\ImageProcessor;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\StatusProcessor;
use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Helper\ProductImageHelper;

/**
 * Sample Product grid data provider. Uses GridBuilder for reusability.
 * Example of how to configure a grid for any Doctrine entity.
 */
class SampleProductDataProvider implements GridDataProviderInterface
{
    private readonly GenericGridDataProvider $inner;

    public function __construct(
        ManagerRegistry $doctrine,
        AttachmentManager $attachmentManager,
        ProductImageHelper $productImageHelper
    ) {
        $config = (new GridBuilder($doctrine))
            ->setEntity(Product::class)
            ->setFields([
                'id' => 'ID',
                'image' => 'Image',
                'sku' => 'SKU',
                'denormalizedDefaultName' => 'Name',
                'status' => 'Status',
                'type' => 'Type',
                'createdAt' => 'Created',
            ])
            ->addProcessor('createdAt', new DateProcessor())
            ->addProcessor('status', new StatusProcessor())
            ->addProcessor('image', new ImageProcessor($attachmentManager, $productImageHelper))
            ->setDefaultSort('sku', 'asc')
            ->build();
        $this->inner = new GenericGridDataProvider($doctrine, $config);
    }

    #[\Override]
    public function getJsonGridData(
        array $fields,
        array $filters,
        int $page,
        int $pageSize,
        ?string $sortField,
        ?string $sortOrder
    ): array {
        return $this->inner->getJsonGridData($fields, $filters, $page, $pageSize, $sortField, $sortOrder);
    }
}
