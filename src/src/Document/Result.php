<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="results", repositoryClass="App\Repository\ResultsRepository")
 */
class Result
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $tournamentId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $playerId;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $place;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $points;

    /**
     * @MongoDB\Field(type="string")
     */
    private $army;

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
}
