<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Model\ResourceModel\Game;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'game_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Xcom\MemoryGame\Model\Game::class,
            \Xcom\MemoryGame\Model\ResourceModel\Game::class
        );
    }
}

