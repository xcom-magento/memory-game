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
use Xcom\MemoryGame\Api\Data\GameInterface;
use Xcom\MemoryGame\Api\Data\GameInterfaceFactory;
use Xcom\MemoryGame\Api\Data\GameSearchResultsInterfaceFactory;
use Xcom\MemoryGame\Api\GameRepositoryInterface;
use Xcom\MemoryGame\Model\ResourceModel\Game as ResourceGame;
use Xcom\MemoryGame\Model\ResourceModel\Game\CollectionFactory as GameCollectionFactory;

class GameRepository implements GameRepositoryInterface
{

    /**
     * @var ResourceGame
     */
    protected $resource;

    /**
     * @var GameInterfaceFactory
     */
    protected $gameFactory;

    /**
     * @var GameCollectionFactory
     */
    protected $gameCollectionFactory;

    /**
     * @var Game
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceGame $resource
     * @param GameInterfaceFactory $gameFactory
     * @param GameCollectionFactory $gameCollectionFactory
     * @param GameSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceGame $resource,
        GameInterfaceFactory $gameFactory,
        GameCollectionFactory $gameCollectionFactory,
        GameSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->gameFactory = $gameFactory;
        $this->gameCollectionFactory = $gameCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(GameInterface $game)
    {
        try {
            $this->resource->save($game);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the game: %1',
                $exception->getMessage()
            ));
        }
        return $game;
    }

    /**
     * @inheritDoc
     */
    public function get($gameId)
    {
        $game = $this->gameFactory->create();
        $this->resource->load($game, $gameId);
        if (!$game->getGameId()) {
            throw new NoSuchEntityException(__('Game with id "%1" does not exist.', $gameId));
        }
        return $game;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->gameCollectionFactory->create();

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
    public function delete(GameInterface $game)
    {
        try {
            $gameModel = $this->gameFactory->create();
            $this->resource->load($gameModel, $game->getGameId());
            $this->resource->delete($gameModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Game: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($gameId)
    {
        return $this->delete($this->get($gameId));
    }
}

