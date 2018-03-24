<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Tournament
 * @MongoDB\Document(repositoryClass="App\Repository\TournamentRepository", collection="tournaments")
 */
class Tournament
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $legacyId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $name;

    /**
     * @MongoDB\Field(type="string")
     */
    private $date;

    /**
     * @MongoDB\Field(type="string")
     */
    private $town;

    /**
     * @MongoDB\Field(type="string")
     */
    private $venue;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $points;

    /**
     * @MongoDB\Field(type="string")
     */
    private $rulesUrl;

    /**
     * @MongoDB\Field(type="string")
     */
    private $organiser;

    /**
     * @MongoDB\Field(type="string")
     */
    private $season;

    /**
     * @MongoDB\Field(type="string")
     */
    private $type;

    /**
     * @MongoDB\Field(type="int")
     */
    private $playersInTeam;

    /**
     * @MongoDB\Field(type="string")
     */
    private $rank;

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
    public function getLegacyId()
    {
        return $this->legacyId;
    }

    /**
     * @param mixed $legacyId
     */
    public function setLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param mixed $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * @return mixed
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @param mixed $venue
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;
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
    public function getRulesUrl()
    {
        return $this->rulesUrl;
    }

    /**
     * @param mixed $rulesUrl
     */
    public function setRulesUrl($rulesUrl)
    {
        $this->rulesUrl = $rulesUrl;
    }

    /**
     * @return mixed
     */
    public function getOrganiser()
    {
        return $this->organiser;
    }

    /**
     * @param mixed $organiser
     */
    public function setOrganiser($organiser)
    {
        $this->organiser = $organiser;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param mixed $season
     */
    public function setSeason($season)
    {
        $this->season = $season;
    }

    /**
     * @return mixed
     */
    public function getPlayersInTeam()
    {
        return $this->playersInTeam;
    }

    /**
     * @param mixed $playersInTeam
     */
    public function setPlayersInTeam($playersInTeam)
    {
        $this->playersInTeam = $playersInTeam;
    }


}
