<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Model;

use Magento\Framework\Model\AbstractModel;
use Xcom\MemoryGame\Api\Data\PlayerInterface;

class Player extends AbstractModel implements PlayerInterface
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Xcom\MemoryGame\Model\ResourceModel\Player::class);
    }

    /**
     * @inheritDoc
     */
    public function getPlayerId()
    {
        return $this->getData(self::PLAYER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPlayerId($playerId)
    {
        return $this->setData(self::PLAYER_ID, $playerId);
    }

    /**
     * @inheritDoc
     */
    public function getSessionId()
    {
        return $this->getData(self::SESSION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSessionId($sessionId)
    {
        return $this->setData(self::SESSION_ID, $sessionId);
    }

    /**
     * @inheritDoc
     */
    public function getScore()
    {
        return $this->getData(self::SCORE);
    }

    /**
     * @inheritDoc
     */
    public function setScore($score)
    {
        return $this->setData(self::SCORE, $score);
    }
}

