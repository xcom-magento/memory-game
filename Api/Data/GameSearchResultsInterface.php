<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Api\Data;

interface GameSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Game list.
     * @return \Xcom\MemoryGame\Api\Data\GameInterface[]
     */
    public function getItems();

    /**
     * Set game_id list.
     * @param \Xcom\MemoryGame\Api\Data\GameInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

