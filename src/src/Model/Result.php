<?php
namespace App\Model;


class Result
{
    private $id;
    private $seasonId;
    private $tournamentId;
    private $tournamentRank;
    private $tournamentType;
    private $playerId;
    private $place;
    private $points;
    private $army;
    private $judge;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * @param mixed $tournamentId
     */
    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = $tournamentId;
    }

    /**
     * @return mixed
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @param mixed $playerId
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getArmy()
    {
        return $this->army;
    }

    /**
     * @param mixed $army
     */
    public function setArmy($army)
    {
        $this->army = $army;
    }

    /**
     * @return mixed
     */
    public function getTournamentRank()
    {
        return $this->tournamentRank;
    }

    /**
     * @param mixed $tournamentRank
     */
    public function setTournamentRank($tournamentRank): void
    {
        $this->tournamentRank = $tournamentRank;
    }

    /**
     * @return mixed
     */
    public function getTournamentType()
    {
        return $this->tournamentType;
    }

    /**
     * @param mixed $tournamentType
     */
    public function setTournamentType($tournamentType): void
    {
        $this->tournamentType = $tournamentType;
    }

    /**
     * @return mixed
     */
    public function getJudge()
    {
        return $this->judge;
    }

    /**
     * @param mixed $judge
     */
    public function setJudge($judge)
    {
        $this->judge = $judge;
    }

    /**
     * @return mixed
     */
    public function getSeasonId()
    {
        return $this->seasonId;
    }

    /**
     * @param mixed $seasonId
     */
    public function setSeasonId($seasonId)
    {
        $this->seasonId = $seasonId;
    }
}
