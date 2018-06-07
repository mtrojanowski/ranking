<?php
/**
 * Created by IntelliJ IDEA.
 * User: michal.trojanowski
 * Date: 29/05/2018
 * Time: 21:08
 */

namespace App\Controller\dto;


class RankingPlayerDto
{
    private $firstName;
    private $nickname;
    private $town;

    /**
     * RankingPlayerDto constructor.
     * @param $firstName
     * @param $nickname
     * @param $town
     */
    public function __construct($firstName, $nickname, $town)
    {
        $this->firstName = $firstName;
        $this->nickname = $nickname;
        $this->town = $town;
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
