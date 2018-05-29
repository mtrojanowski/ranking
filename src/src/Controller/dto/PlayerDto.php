<?php
namespace App\Controller\dto;

class PlayerDto
{
    private $legacyId;
    private $firstName;
    private $town;

    /**
     * PlayerDto constructor.
     * @param $legacyId
     * @param $firstName
     * @param $town
     */
    public function __construct($legacyId, $firstName, $town)
    {
        $this->legacyId = $legacyId;
        $this->firstName = $firstName;
        $this->town = $town;
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
    public function getTown()
    {
        return $this->town;
    }
}