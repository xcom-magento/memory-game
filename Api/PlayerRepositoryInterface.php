<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PlayerRepositoryInterface
{

    /**
     * Save Player
     * @param \Xcom\MemoryGame\Api\Data\PlayerInterface $player
     * @return \Xcom\MemoryGame\Api\Data\PlayerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Xcom\MemoryGame\Api\Data\PlayerInterface $player
    );

    /**
     * Retrieve Player
     * @param string $playerId
     * @return \Xcom\MemoryGame\Api\Data\PlayerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($playerId);

    /**
     * Retrieve Player by session id
     * @param string $sessionId
     * @return \Xcom\MemoryGame\Api\Data\PlayerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBySessionId($sessionId);

    /**
     * Retrieve Player matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Xcom\MemoryGame\Api\Data\PlayerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Player
     * @param \Xcom\MemoryGame\Api\Data\PlayerInterface $player
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Xcom\MemoryGame\Api\Data\PlayerInterface $player
    );

    /**
     * Delete Player by ID
     * @param string $playerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($playerId);
}

