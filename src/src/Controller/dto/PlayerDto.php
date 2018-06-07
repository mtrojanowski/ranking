<?php
namespace App\Controller\dto;

class PlayerDto
{
    private $legacyId;
    private $firstName;
    private $nickname;
    private $town;

    /**
     * PlayerDto constructor.
     * @param $legacyId
     * @param $firstName
     * @param $nickname
     * @param $town
     */
    public function __construct($legacyId, $firstName, $nickname, $town)
    {
        $this->legacyId = $legacyId;
        $this->firstName = $firstName;
        $this->nickname = $nickname;
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
}