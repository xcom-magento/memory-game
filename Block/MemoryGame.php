<?php

namespace Xcom\MemoryGame\Block;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;

class MemoryGame extends Template
{
    /**
     * @param Context $context
     * @param Json $jsonSerializer
     * @param ProductRepositoryInterface $productRepository
     * @param ProductHelper $productHelper
     */
    public function __construct(
        Context $context,
        protected Json $jsonSerializer,
        protected ProductRepositoryInterface $productRepository,
        protected ProductHelper $productHelper
    ) {
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getMemoryGame(): string
    {
        return __('MemoryGame')->render();
    }

    /**
     * @return array
     */
    public function getMemoryGameConfig(): array
    {
        $config = [];

        $config['cards'] = $this->getMemoryCards();

        return $config;
        //return $this->jsonSerializer->serialize($config);
    }

    /**
     * @return array
     */
    private function getMemoryCards(): array
    {
        $cards = [];
        $products = [1,2,3,4,5];

        foreach ($products as $productId) {
            try {
                $product = $this->productRepository->getById($productId);
                if ($product) {
                    $cards[] = [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'url' => $this->productHelper->getImageUrl($product)
                    ];
                    $cards[] = [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'url' => $this->productHelper->getImageUrl($product)
                    ];
                }
            } catch (\Exception) {

            }
        }

        shuffle($cards);
        return $cards;
    }
}
