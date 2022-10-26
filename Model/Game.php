<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Model;

use Magento\Framework\Model\AbstractModel;
use Xcom\MemoryGame\Api\Data\GameInterface;

class Game extends AbstractModel implements GameInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Xcom\MemoryGame\Model\ResourceModel\Game::class);
    }

    /**
     * @inheritDoc
     */
    public function getGameId()
    {
        return $this->getData(self::GAME_ID);
    }

    /**
     * @inheritDoc
     */
    public function setGameId($gameId)
    {
        return $this->setData(self::GAME_ID, $gameId);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getPlayer1()
    {
        return $this->getData(self::PLAYER1);
    }

    /**
     * @inheritDoc
     */
    public function setPlayer1($player1)
    {
        return $this->setData(self::PLAYER1, $player1);
    }

    /**
     * @inheritDoc
     */
    public function getPlayer2()
    {
        return $this->getData(self::PLAYER2);
    }

    /**
     * @inheritDoc
     */
    public function setPlayer2($player2)
    {
        return $this->setData(self::PLAYER2, $player2);
    }

    /**
     * @inheritDoc
     */
    public function getActivePlayer()
    {
        return $this->getData(self::ACTIVE_PLAYER);
    }

    /**
     * @inheritDoc
     */
    public function setActivePlayer($activePlayer)
    {
        return $this->setData(self::ACTIVE_PLAYER, $activePlayer);
    }

    /**
     * @inheritDoc
     */
    public function getGameConfig()
    {
        return $this->getData(self::GAME_CONFIG);
    }

    /**
     * @inheritDoc
     */
    public function setGameConfig($gameConfig)
    {
        return $this->setData(self::GAME_CONFIG, $gameConfig);
    }
}

