<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Game extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('memory_game_game', 'game_id');
    }
}

