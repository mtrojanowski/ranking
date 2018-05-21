<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="App\Repository\RankingRepository", collection="ranking")
 */
class Ranking
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $playerId;

    /**
     * @MongoDb\EmbedOne(targetDocument="RankingPlayer")
     */
    private $player;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $points;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $tournamentCount;

    /**
     * @MongoDB\Field(type="collection")
     */
    private $tournamentsIncluded;

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
    public function setId($id): void
    {
        $this->id = $id;
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
    public function setPlayerId($playerId): void
    {
        $this->playerId = $playerId;
    }

    /**
     * @return mixed
     */
    public function getPlayer() : RankingPlayer
    {
        return $this->player;
    }

    /**
     * @param mixed $player
     */
    public function setPlayer($player): void
    {
        $this->player = $player;
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
    public function setPoints($points): void
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getTournamentCount()
    {
        return $this->tournamentCount;
    }

    /**
     * @param mixed $tournamentCount
     */
    public function setTournamentCount($tournamentCount): void
    {
        $this->tournamentCount = $tournamentCount;
    }

    /**
     * @return mixed
     */
    public function getTournamentsIncluded()
    {
        return $this->tournamentsIncluded;
    }

    /**
     * @param mixed $tournamentsIncluded
     */
    public function setTournamentsIncluded($tournamentsIncluded): void
    {
        $this->tournamentsIncluded = $tournamentsIncluded;
    }
}
