<?php

namespace Xcom\MemoryGame\Magewire;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magewirephp\Magewire\Component;
use Xcom\MemoryGame\Api\Data\GameInterface;
use Xcom\MemoryGame\Api\Data\PlayerInterface;
use Xcom\MemoryGame\Api\PlayerRepositoryInterface;
use Xcom\MemoryGame\Model\Player;
use Xcom\MemoryGame\Api\Data\PlayerInterfaceFactory;
use Xcom\MemoryGame\Api\GameRepositoryInterface;
use Xcom\MemoryGame\Api\Data\GameInterfaceFactory;

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
        protected PlayerInterfaceFactory $playerFactory,
        protected GameRepositoryInterface $gameRepository,
        protected GameInterfaceFactory $gameFactory
    ) {
    }

    public function mount($properties, ...$request): void
    {
        parent::mount($properties, $request);

        $player = $this->getPlayer();
        $gameConfig = [
            'cards' => $this->cards
        ];
        $game = $this->getGame($player, $gameConfig);

        $gameConfig = $this->jsonSerializer->unserialize($game->getGameConfig());
        if (count($gameConfig['cards']) == 0) {
            $this->cards = $this->dealCards();
            $gameConfig['cards'] = $this->cards;
            $this->updateGameConfig($game, $gameConfig);
        } else {
            $this->cards = $gameConfig['cards'];
        }
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
        $count = 0;

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

                    $cards[$count++] = $productInfo;
                    $cards[$count++] = $productInfo;
                }
            } catch (\Exception) {

            }
        }

        shuffle($cards);
        return $cards;
    }

    /**
     * @param string $cardPosition
     * @return void
     */
    public function turnCard(string $cardPosition): void
    {
        $this->cards[$cardPosition]['status'] = 1;
    }

    /**
     * @return PlayerInterface
     * @throws LocalizedException
     */
    private function getPlayer(): PlayerInterface
    {
        $sessionId = session_id();
        try {
            $player = $this->playerRepository->getBySessionId($sessionId);
        } catch (\Exception) {
            $player = $this->playerFactory->create();
            $player->setSessionId($sessionId)
                ->setScore(0);
            $this->playerRepository->save($player);
        }
        return $player;
    }

    /**
     * @param $player
     * @param $gameConfig
     * @return GameInterface
     * @throws LocalizedException
     */
    private function getGame($player, $gameConfig): GameInterface
    {
        try {
            /* Get open games */
            $game = $this->gameRepository->get(1);
        } catch (\Exception) {
            $game = $this->gameFactory->create();
            $game->setStatus('waiting_for_players')
                ->setPlayer1($player->getPlayerId())
                ->setGameConfig($this->jsonSerializer->serialize($gameConfig));
            $this->gameRepository->save($game);
        }
        return $game;
    }

    /**
     * @param GameInterface $game
     * @param $gameConfig
     * @return void
     * @throws LocalizedException
     */
    private function updateGameConfig(GameInterface $game, $gameConfig): void
    {
        $game->setGameConfig($this->jsonSerializer->serialize($gameConfig));
        $this->gameRepository->save($game);
    }
}
