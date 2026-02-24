<?php

namespace Genaker\Bundle\DataGridBundle\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Genaker\Bundle\DataGridBundle\Builder\GridBuilder;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\DateProcessor;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\ImageProcessor;
use Genaker\Bundle\DataGridBundle\Model\DataProcessor\StatusProcessor;
use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Helper\ProductImageHelper;

/**
 * Sample Product grid data provider. Extends GenericGridDataProvider with Product-specific config.
 */
class SampleProductDataProvider extends GenericGridDataProvider
{
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
            ->addJoin('e.images', 'pi')
            ->addJoin('pi.image', 'img')
            ->addProcessor('createdAt', new DateProcessor())
            ->addProcessor('status', new StatusProcessor())
            ->addProcessor('image', new ImageProcessor($attachmentManager, $productImageHelper))
            ->setDefaultSort('sku', 'asc')
            ->build();
        parent::__construct($doctrine, $config);
    }
}
