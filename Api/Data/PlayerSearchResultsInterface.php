<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Api\Data;

interface PlayerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Player list.
     * @return \Xcom\MemoryGame\Api\Data\PlayerInterface[]
     */
    public function getItems();

    /**
     * Set player list.
     * @param \Xcom\MemoryGame\Api\Data\PlayerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

