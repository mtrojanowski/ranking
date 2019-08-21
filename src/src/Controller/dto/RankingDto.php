<?php

namespace App\Controller\dto;


class RankingDto
{
    private $id;
    private $player;
    private $points;
    private $tournamentCount;
    private $tournamentsIncluded;
    private $tournamentsAttendedCount;

    /**
     * RankingDto constructor.
     * @param $id
     * @param $player
     * @param $points
     * @param $tournamentCount
     * @param $tournamentsIncluded
     * @param $tournamentsAttendedCount
     */
    public function __construct(string $id, RankingPlayerDto $player, int $points, int $tournamentCount, array $tournamentsIncluded, int $tournamentsAttendedCount)
    {
        $this->id = $id;
        $this->player = $player;
        $this->points = $points;
        $this->tournamentCount = $tournamentCount;
        $this->tournamentsIncluded = $tournamentsIncluded;
        $this->tournamentsAttendedCount = $tournamentsAttendedCount;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlayer(): RankingPlayerDto
    {
        return $this->player;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getTournamentCount(): int
    {
        return $this->tournamentCount;
    }

    /**
     * @return array Array of strings - tournament ids
     */
    public function getTournamentsIncluded(): array
    {
        return $this->tournamentsIncluded;
    }

    public function getTournamentsAttendedCount(): int
    {
        return $this->tournamentsAttendedCount;
    }
}
