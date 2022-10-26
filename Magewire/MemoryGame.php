<?php

namespace Xcom\MemoryGame\Magewire;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magewirephp\Magewire\Component;

class MemoryGame extends Component
{
    public function __construct(
        protected Json $jsonSerializer,
        protected ProductRepositoryInterface $productRepository,
        protected ProductHelper $productHelper
    ) {
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
