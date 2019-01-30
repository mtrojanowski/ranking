<?php
namespace App\Controller\dto;

class TournamentDataResult
{
    private $place;
    private $playerId;
    private $judge;
    private $points;
    private $playerName;
    private $playerNickname;
    private $playerFirstName;
    private $playerAssociation;
    private $playerTown;

    /**
     * TournamentDataResult constructor.
     * @param int $place
     * @param string $playerId
     * @param int $judge
     * @param int $points
     * @param string $playerName
     * @param string $playerNickname
     * @param string $playerFirstName
     * @param string $playerAssociation
     * @param string $playerTown
     */
    public function __construct(int $place, string $playerId, int $judge, int $points, string $playerName, string $playerNickname, string $playerFirstName, string $playerAssociation, string $playerTown)
    {
        $this->place = $place;
        $this->playerId = $playerId;
        $this->judge = $judge;
        $this->points = $points;
        $this->playerName = $playerName;
        $this->playerNickname = $playerNickname;
        $this->playerFirstName = $playerFirstName;
        $this->playerAssociation = $playerAssociation;
        $this->playerTown = $playerTown;
    }


    /**
     * @return int
     */
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param int $place
     */
    public function setPlace(int $place): void
    {
        $this->place = $place;
    }

    /**
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    /**
     * @param string $playerId
     */
    public function setPlayerId(string $playerId): void
    {
        $this->playerId = $playerId;
    }

    /**
     * @return int
     */
    public function getJudge(): int
    {
        return $this->judge;
    }

    /**
     * @param int $judge
     */
    public function setJudge(int $judge): void
    {
        $this->judge = $judge;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    /**
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    /**
     * @param string $playerName
     */
    public function setPlayerName(string $playerName): void
    {
        $this->playerName = $playerName;
    }

    /**
     * @return string
     */
    public function getPlayerAssociation(): string
    {
        return $this->playerAssociation;
    }

    /**
     * @param string $playerAssociation
     */
    public function setPlayerAssociation(string $playerAssociation): void
    {
        $this->playerAssociation = $playerAssociation;
    }

    /**
     * @return string
     */
    public function getPlayerTown(): string
    {
        return $this->playerTown;
    }

    /**
     * @param string $playerTown
     */
    public function setPlayerTown(string $playerTown): void
    {
        $this->playerTown = $playerTown;
    }

    /**
     * @return string
     */
    public function getPlayerNickname(): string
    {
        return $this->playerNickname;
    }

    /**
     * @param string $playerNickname
     */
    public function setPlayerNickname(string $playerNickname): void
    {
        $this->playerNickname = $playerNickname;
    }

    /**
     * @return string
     */
    public function getPlayerFirstName(): string
    {
        return $this->playerFirstName;
    }

    /**
     * @param string $playerFirstName
     */
    public function setPlayerFirstName(string $playerFirstName): void
    {
        $this->playerFirstName = $playerFirstName;
    }

}