<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Xcom\MemoryGame\Api\Data\PlayerInterface;
use Xcom\MemoryGame\Api\Data\PlayerInterfaceFactory;
use Xcom\MemoryGame\Api\Data\PlayerSearchResultsInterfaceFactory;
use Xcom\MemoryGame\Api\PlayerRepositoryInterface;
use Xcom\MemoryGame\Model\ResourceModel\Player as ResourcePlayer;
use Xcom\MemoryGame\Model\ResourceModel\Player\CollectionFactory as PlayerCollectionFactory;

class PlayerRepository implements PlayerRepositoryInterface
{

    /**
     * @var ResourcePlayer
     */
    protected $resource;

    /**
     * @var PlayerInterfaceFactory
     */
    protected $playerFactory;

    /**
     * @var PlayerCollectionFactory
     */
    protected $playerCollectionFactory;

    /**
     * @var Player
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourcePlayer $resource
     * @param PlayerInterfaceFactory $playerFactory
     * @param PlayerCollectionFactory $playerCollectionFactory
     * @param PlayerSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourcePlayer $resource,
        PlayerInterfaceFactory $playerFactory,
        PlayerCollectionFactory $playerCollectionFactory,
        PlayerSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->playerFactory = $playerFactory;
        $this->playerCollectionFactory = $playerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(PlayerInterface $player)
    {
        try {
            $this->resource->save($player);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the player: %1',
                $exception->getMessage()
            ));
        }
        return $player;
    }

    /**
     * @inheritDoc
     */
    public function get($playerId)
    {
        $player = $this->playerFactory->create();
        $this->resource->load($player, $playerId);
        if (!$player->getPlayerId()) {
            throw new NoSuchEntityException(__('Player with id "%1" does not exist.', $playerId));
        }
        return $player;
    }

    /**
     * @inheritDoc
     */
    public function getBySessionId($sessionId)
    {
        $player = $this->playerFactory->create();
        $this->resource->load($player, $sessionId, 'session_id');
        if (!$player->getPlayerId()) {
            throw new NoSuchEntityException(__('Player with session_id "%1" does not exist.', $sessionId));
        }
        return $player;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->playerCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(PlayerInterface $player)
    {
        try {
            $playerModel = $this->playerFactory->create();
            $this->resource->load($playerModel, $player->getPlayerId());
            $this->resource->delete($playerModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the player: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($playerId)
    {
        return $this->delete($this->get($playerId));
    }
}

