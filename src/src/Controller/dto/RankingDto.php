<?php
/**
 * Created by IntelliJ IDEA.
 * User: michal.trojanowski
 * Date: 29/05/2018
 * Time: 21:07
 */

namespace App\Controller\dto;


class RankingDto
{
    private $id;
    private $player;
    private $points;
    private $tournamentCount;
    private $tournamentsIncluded;

    /**
     * RankingDto constructor.
     * @param $id
     * @param $player
     * @param $points
     * @param $tournamentCount
     * @param $tournamentsIncluded
     */
    public function __construct($id, $player, $points, $tournamentCount, $tournamentsIncluded)
    {
        $this->id = $id;
        $this->player = $player;
        $this->points = $points;
        $this->tournamentCount = $tournamentCount;
        $this->tournamentsIncluded = $tournamentsIncluded;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return mixed
     */
    public function getTournamentCount()
    {
        return $this->tournamentCount;
    }

    /**
     * @return mixed
     */
    public function getTournamentsIncluded()
    {
        return $this->tournamentsIncluded;
    }
}
