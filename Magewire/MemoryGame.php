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
use Xcom\MemoryGame\Model\Game;
use Xcom\MemoryGame\Api\Data\PlayerInterfaceFactory;
use Xcom\MemoryGame\Api\GameRepositoryInterface;
use Xcom\MemoryGame\Api\Data\GameInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;

class MemoryGame extends Component
{
    /**
     * @var array
     */
    public array $cards = [];

    public $nrOfClicks = 0;

    /**
     * @var int
     */
    public int $firstCardClicked = -1;

    public function __construct(
        protected Json $jsonSerializer,
        protected ProductRepositoryInterface $productRepository,
        protected ProductHelper $productHelper,
        protected PlayerRepositoryInterface $playerRepository,
        protected PlayerInterfaceFactory $playerFactory,
        protected GameRepositoryInterface $gameRepository,
        protected GameInterfaceFactory $gameFactory,
        protected SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function mount($properties, ...$request): void
    {
        //$this->nrOfClicks = 0;

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

    public function checkYourTurn(): int
    {
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
        /** TODO first check if it is your turn */

        /** check if the first card is clicked a second time */
        if ($this->firstCardClicked == $cardPosition) {
            return;
        }

        $this->nrOfClicks ++;
        $this->cards[$cardPosition]['status'] = 1;

        /** save your first card */
        if ($this->nrOfClicks == 1) {
            $this->firstCardClicked = $cardPosition;
        }

        if ($this->nrOfClicks == 2) {
            /**
             * check if cards are the same
             * yes: update score and play again
             * no: end turn
             */

            $this->nrOfClicks = 0;
            $this->cards[$this->firstCardClicked]['status'] = 0;
            $this->cards[$cardPosition]['status'] = 0;
            $this->firstCardClicked = -1;

        }
    }

    public function hydrateNrOfClicks($value)
    {
        return $value;
    }

    public function dehydrateNrOfClicks($value)
    {
        return $value;
    }

    /**
     * @return PlayerInterface
     * @throws LocalizedException
     */
    private function getPlayer(): PlayerInterface
    {
        $sessionId = session_id();
        try {
            $this->searchCriteriaBuilder->addFilter('status', Game::STATUS_WAITING_FOR_PLAYERS);
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
            $this->searchCriteriaBuilder->addFilter('status', Game::STATUS_WAITING_FOR_PLAYERS);
            $waitingGames = $this->gameRepository->getList($this->searchCriteriaBuilder->create())->getItems();

            if (count($waitingGames)) {
                $game = reset($waitingGames);
            } else {
                $game = $this->createNewGame($gameConfig);
            }
        } catch (\Exception) {
            $game = $this->createNewGame($gameConfig);
        }

        $game->addPlayer($player);

        $this->saveGame($game);
        return $game;
    }

    /**
     * @param $gameConfig
     * @return GameInterface
     * @throws LocalizedException
     */
    private function createNewGame($gameConfig): GameInterface
    {
        $game = $this->gameFactory->create();
        $game->setStatus(Game::STATUS_WAITING_FOR_PLAYERS)
            ->setGameConfig($this->jsonSerializer->serialize($gameConfig));
        $this->saveGame($game);

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
        $this->saveGame($game);
    }

    /**
     * @param GameInterface $game
     * @param $gameConfig
     * @return void
     * @throws LocalizedException
     */
    private function saveGame(GameInterface $game): void
    {
        $this->gameRepository->save($game);
    }
}
