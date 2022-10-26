<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xcom\MemoryGame\Api\Data;

use Xcom\MemoryGame\Model\Game;

interface GameInterface
{
    public const GAME_ID = 'game_id';
    public const STATUS = 'status';
    public const PLAYER1 = 'player1';
    public const PLAYER2 = 'player2';
    public const ACTIVE_PLAYER = 'active_player';
    public const GAME_CONFIG = 'game_config';

    public function getGameId();
    public function setGameId($gameId);

    public function getStatus();
    public function setStatus($status);

    public function getPlayer1();
    public function setPlayer1($player1);

    public function getPlayer2();
    public function setPlayer2($player2);

    public function getActivePlayer();
    public function setActivePlayer($activePlayer);

    public function getGameConfig();
    public function setGameConfig($gameConfig);

    public function addPlayer(PlayerInterface $player): Game;
}

