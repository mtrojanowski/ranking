<?php
namespace App\Controller\dto;

class PlayerDto
{
    private $legacyId;
    private $firstName;
    private $nickname;
    private $town;
    private $country;
    private $association;

    /**
     * PlayerDto constructor.
     * @param $legacyId
     * @param $firstName
     * @param $nickname
     * @param $town
     * @param $country
     * @param $association
     */
    public function __construct($legacyId, $firstName, $nickname, $town, $country, $association)
    {
        $this->legacyId = $legacyId;
        $this->firstName = $firstName;
        $this->nickname = $nickname;
        $this->town = $town;
        $this->country = $country;
        $this->association = $association;
    }

    /**
     * @return mixed
     */
    public function getLegacyId()
    {
        return $this->legacyId;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getAssociation()
    {
        return $this->association;
    }
}
