<?php

namespace Xcom\MemoryGame\Magewire;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magewirephp\Magewire\Component;
use Xcom\MemoryGame\Api\PlayerRepositoryInterface;
use Xcom\MemoryGame\Model\PlayerFactory;

class MemoryGame extends Component
{
    /**
     * @var array
     */
    public array $cards = [];

    public function __construct(
        protected Json $jsonSerializer,
        protected ProductRepositoryInterface $productRepository,
        protected ProductHelper $productHelper,
        protected PlayerRepositoryInterface $playerRepository,
        protected PlayerFactory $playerFactory
    ) {
    }

    public function initialize()
    {
        $this->cards = $this->dealCards();
    }

    public function checkYourTurn() {
        return rand(1,100);
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
    private function dealCards(): array
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
                        'url' => $this->productHelper->getImageUrl($product),
                        'status' => 0
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
    public function turnCard(): string
    {
        return '';
    }
}
