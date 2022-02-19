<?php

namespace App\Controller\dto;

class SeasonDto
{
    private string $seasonId;
    private string $name;

    public function __construct(string $seasonId, string $name)
    {
        $this->seasonId = $seasonId;
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getSeasonId(): string
    {
        return $this->seasonId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}
