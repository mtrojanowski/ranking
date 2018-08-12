<?php
namespace App\Controller\dto;

class IndividualRankingTournamentDto
{
    private $tournamentId;
    private $legacyId;
    private $tournamentDate;
    private $tournamentName;
    private $tournamentRank;
    private $tournamentType;
    private $tournamentPlayersInTeam;
    private $playersPlace;
    private $playersPoints;
    private $playersArmy;
    private $tournamentPointsIncluded;
    private $playerWasAJudge;

    /**
     * IndividualRankingTournamentDto constructor.
     * @param string $tournamentId
     * @param int $legacyId
     * @param string $tournamentDate
     * @param string $tournamentName
     * @param string $tournamentRank
     * @param string $tournamentType
     * @param int $tournamentPlayersInTeam
     * @param int $playersPlace
     * @param int $playersPoints
     * @param string $playersArmy
     * @param bool $tournamentPointsIncluded
     * @param bool $playerWasAJudge
     */
    public function __construct(string $tournamentId, int $legacyId, string $tournamentDate, string $tournamentName, string $tournamentRank, string $tournamentType, int $tournamentPlayersInTeam, int $playersPlace, int $playersPoints, string $playersArmy, bool $tournamentPointsIncluded, bool $playerWasAJudge)
    {
        $this->tournamentId = $tournamentId;
        $this->legacyId = $legacyId;
        $this->tournamentDate = $tournamentDate;
        $this->tournamentName = $tournamentName;
        $this->tournamentRank = $tournamentRank;
        $this->tournamentType = $tournamentType;
        $this->tournamentPlayersInTeam = $tournamentPlayersInTeam;
        $this->playersPlace = $playersPlace;
        $this->playersPoints = $playersPoints;
        $this->playersArmy = $playersArmy;
        $this->tournamentPointsIncluded = $tournamentPointsIncluded;
        $this->playerWasAJudge = $playerWasAJudge;
    }


    /**
     * @return string
     */
    public function getTournamentId() : string
    {
        return $this->tournamentId;
    }

    /**
     * @return string
     */
    public function getTournamentDate() : string
    {
        return $this->tournamentDate;
    }

    /**
     * @return string
     */
    public function getTournamentName() : string
    {
        return $this->tournamentName;
    }

    /**
     * @return string
     */
    public function getTournamentRank() : string
    {
        return $this->tournamentRank;
    }

    /**
     * @return string
     */
    public function getTournamentType() : string
    {
        return $this->tournamentType;
    }

    /**
     * @return int
     */
    public function getTournamentPlayersInTeam() : int
    {
        return $this->tournamentPlayersInTeam;
    }

    /**
     * @return int
     */
    public function getPlayersPlace() : int
    {
        return $this->playersPlace;
    }

    /**
     * @return int
     */
    public function getPlayersPoints() : int
    {
        return $this->playersPoints;
    }

    /**
     * @return string
     */
    public function getPlayersArmy() : string
    {
        return $this->playersArmy;
    }

    /**
     * @return bool
     */
    public function getTournamentPointsIncluded() : bool
    {
        return $this->tournamentPointsIncluded;
    }

    /**
     * @return bool
     */
    public function getPlayerWasAJudge() : bool
    {
        return $this->playerWasAJudge;
    }

    /**
     * @return int
     */
    public function getLegacyId(): int
    {
        return $this->legacyId;
    }
}
