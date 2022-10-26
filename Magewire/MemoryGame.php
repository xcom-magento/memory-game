<?php

namespace Xcom\MemoryGame\Magewire;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magewirephp\Magewire\Component;

class MemoryGame extends Component
{
    /**
     * @param Json $jsonSerializer
     * @param ProductRepositoryInterface $productRepository
     * @param ProductHelper $productHelper
     */
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
        $products = [1,2,3,4,5,6];

        foreach ($products as $productId) {
            try {
                $product = $this->productRepository->getById($productId);
                if ($product) {
                    $productInfo = [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'url' => $this->productHelper->getImageUrl($product)
                    ];
                    $cards[] = $productInfo;
                    $cards[] = $productInfo;
                }
            } catch (\Exception) {

            }
        }

        shuffle($cards);
        return $cards;
    }

    /**
     * @return string
     */
    public function foo()
    {
        return "dsds";
    }
}
