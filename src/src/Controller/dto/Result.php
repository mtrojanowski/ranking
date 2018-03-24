<?php
namespace App\Controller\dto;

class Result
{
    private $playerId;
    private $army;
    private $place;

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


}
