<?php
namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Season
 * @MongoDB\Document(collection="seasons", repositoryClass="App\Repository\SeasonRepository")
 */
class Season
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $name;


    /**
     * @MongoDB\Field(type="date")
     */
    private $startDate;

    /**
     * @MongoDB\Field(type="date")
     */
    private $endDate;

    /**
     * @MongoDB\Field(type="bool")
     */
    private $active;

    /**
     * @MongoDB\Field(type="int")
     */
    private $limitOfTournaments;

    /**
     * @MongoDB\Field(type="int")
     */
    private $limitOfMasterTournaments;

    /**
     * @MongoDB\Field(type="int")
     */
    private $limitOfTeamMasterTournaments;

    /**
     * @MongoDB\Field(type="int")
     */
    private $limitOfPairMasterTournaments;

    /**
     * @MongoDB\Field(type="date")
     */
    private $rankingLastModified;

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
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
    */
    public function getLimitOfTournaments()
    {
        return $this->limitOfTournaments;
    }

    /**
     * @param mixed $limitOfTournaments
     */
    public function setLimitOfTournaments($limitOfTournaments): void
    {
        $this->limitOfTournaments = $limitOfTournaments;
    }

    /**
     * @return mixed
     */
    public function getLimitOfMasterTournaments()
    {
        return $this->limitOfMasterTournaments;
    }

    /**
     * @param mixed $limitOfMasterTournaments
     */
    public function setLimitOfMasterTournaments($limitOfMasterTournaments): void
    {
        $this->limitOfMasterTournaments = $limitOfMasterTournaments;
    }

    /**
     * @return mixed
     */
    public function getLimitOfTeamMasterTournaments()
    {
        return $this->limitOfTeamMasterTournaments;
    }

    /**
     * @param int $limitOfTeamMasterTournaments
     */
    public function setLimitOfTeamMasterTournaments(int $limitOfTeamMasterTournaments): void
    {
        $this->limitOfTeamMasterTournaments = $limitOfTeamMasterTournaments;
    }

    /**
     * @return mixed
     */
    public function getLimitOfPairMasterTournaments()
    {
        return $this->limitOfPairMasterTournaments;
    }

    /**
     * @param mixed $limitOfPairMasterTournaments
     */
    public function setLimitOfPairMasterTournaments($limitOfPairMasterTournaments)
    {
        $this->limitOfPairMasterTournaments = $limitOfPairMasterTournaments;
    }

    /**
     * @return DateTime
     */
    public function getRankingLastModified(): DateTime
    {
        return $this->rankingLastModified;
    }

    /**
     * @param mixed $rankingLastModified
     */
    public function setRankingLastModified($rankingLastModified): void
    {
        $this->rankingLastModified = $rankingLastModified;
    }


}
