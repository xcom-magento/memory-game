<?php

namespace Xcom\MemoryGame\Api\Data;

interface PlayerInterface
{
    public const PLAYER_ID = 'player_id';
    public const SESSION_ID = 'session_id';
    public const SCORE = 'score';

    public function getPlayerId();
    public function setPlayerId($playerId);

    public function getSessionId();
    public function setSessionId($sessionId);

    public function getScore();
    public function setScore($score);
}
