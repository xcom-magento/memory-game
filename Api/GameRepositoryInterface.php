<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface GameRepositoryInterface
{

    /**
     * Save Game
     * @param \Xcom\MemoryGame\Api\Data\GameInterface $game
     * @return \Xcom\MemoryGame\Api\Data\GameInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Xcom\MemoryGame\Api\Data\GameInterface $game
    );

    /**
     * Retrieve Game
     * @param string $gameId
     * @return \Xcom\MemoryGame\Api\Data\GameInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($gameId);

    /**
     * Retrieve Game matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Xcom\MemoryGame\Api\Data\GameSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Game
     * @param \Xcom\MemoryGame\Api\Data\GameInterface $game
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Xcom\MemoryGame\Api\Data\GameInterface $game
    );

    /**
     * Delete Game by ID
     * @param string $gameId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($gameId);
}

