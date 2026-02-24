<?php

namespace Genaker\Bundle\DataGridBundle\Model\DataProcessor;

use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Entity\ProductImage;
use Oro\Bundle\ProductBundle\Helper\ProductImageHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Processes product image to return its thumbnail URL.
 */
class ImageProcessor implements DataProcessorInterface
{
    public function __construct(
        private readonly AttachmentManager $attachmentManager,
        private readonly ProductImageHelper $productImageHelper
    ) {
    }

    #[\Override]
    public function process(string $fieldName, mixed $value, array $row): mixed
    {
        $entity = $row['entity'] ?? null;
        if (!$entity instanceof Product) {
            return '';
        }

        $images = $entity->getImages();
        if ($images->isEmpty()) {
            return '';
        }

        $sortedImages = $this->productImageHelper->sortImages($images->toArray());
        /** @var ProductImage $mainImage */
        $mainImage = reset($sortedImages);

        if (!$mainImage || !$mainImage->getImage()) {
            return '';
        }

        return $this->attachmentManager->getResizedImageUrl(
            $mainImage->getImage(),
            AttachmentManager::THUMBNAIL_WIDTH,
            AttachmentManager::THUMBNAIL_HEIGHT,
            '',
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
