<?php
namespace App\Controller\dto;

class TournamentDataDto
{
    private $id;
    private $legacyId;
    private $name;
    private $town;
    private $date;
    private $venue;
    private $organiser;
    private $results;

    /**
     * TournamentDataDto constructor.
     * @param string $id
     * @param int $legacyId
     * @param string $name
     * @param string $town
     * @param string $date
     * @param string $venue
     * @param string $organiser
     * @param array $results
     */
    public function __construct(string $id, int $legacyId, string $name, string $town, string $date, string $venue, string $organiser, array $results)
    {
        $this->id = $id;
        $this->legacyId = $legacyId;
        $this->name = $name;
        $this->town = $town;
        $this->date = $date;
        $this->venue = $venue;
        $this->organiser = $organiser;
        $this->results = $results;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLegacyId(): int
    {
        return $this->legacyId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTown(): string
    {
        return $this->town;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getVenue(): string
    {
        return $this->venue;
    }

    /**
     * @return string
     */
    public function getOrganiser(): string
    {
        return $this->organiser;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }


}