<?php
namespace App\Controller\dto;


class IndividualRankingDto
{
    private $points;
    private $player;
    private $tournaments;

    /**
     * IndividualRankingDto constructor.
     * @param int $points
     * @param RankingPlayerDto $player
     * @param array $tournaments
     */
    public function __construct(int $points, RankingPlayerDto $player, array $tournaments)
    {
        $this->points = $points;
        $this->player = $player;
        $this->tournaments = $tournaments;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return RankingPlayerDto
     */
    public function getPlayer(): RankingPlayerDto
    {
        return $this->player;
    }

    /**
     * @return array
     */
    public function getTournaments(): array
    {
        return $this->tournaments;
    }
}
