<?php
namespace App\Helper;

class RankingData
{
    private $results;
    private $tournamentLimit;
    private $tournamentsIncluded;
    private $tournamentsIncludedCount;
    private $mastersIncluded;
    private $teamMastersIncluded;
    private $doubleMastersIncluded;
    private $pointsSum;
    private $headJudgeBonusReceived;

    /**
     * RankingData constructor.
     * @param $results
     * @param $tournamentLimit
     */
    public function __construct(array $results, int $tournamentLimit)
    {
        $this->results = $results;
        $this->tournamentLimit = $tournamentLimit;
        $this->tournamentsIncluded = [];
        $this->tournamentsIncludedCount = 0;
        $this->mastersIncluded = 0;
        $this->teamMastersIncluded = 0;
        $this->doubleMastersIncluded = 0;
        $this->pointsSum = 0;
        $this->headJudgeBonusReceived = 0;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getTournamentLimit(): int
    {
        return $this->tournamentLimit;
    }

    /**
     * @return array
     */
    public function getTournamentsIncluded(): array
    {
        return $this->tournamentsIncluded;
    }

    /**
     * @return int
     */
    public function getTournamentsIncludedCount(): int
    {
        return $this->tournamentsIncludedCount;
    }

    /**
     * @return int
     */
    public function getMastersIncluded(): int
    {
        return $this->mastersIncluded;
    }

    /**
     * @return int
     */
    public function getTeamMastersIncluded(): int
    {
        return $this->teamMastersIncluded;
    }

    /**
     * @return int
     */
    public function getDoubleMastersIncluded(): int
    {
        return $this->doubleMastersIncluded;
    }

    /**
     * @return int
     */
    public function getPointsSum(): int
    {
        return $this->pointsSum;
    }

    /**
     * @return int
     */
    public function getHeadJudgeBonusReceived(): int
    {
        return $this->headJudgeBonusReceived;
    }

    /**
     * @param array $results
     */
    public function setResults(array $results)
    {
        $this->results = $results;
    }

    /**
     * @param array $tournamentsIncluded
     */
    public function setTournamentsIncluded(array $tournamentsIncluded)
    {
        $this->tournamentsIncluded = $tournamentsIncluded;
    }

    /**
     * @param int $tournamentId
     * @param int $originalPoints
     */
    public function addIncludedTournament(int $tournamentId, int $originalPoints)
    {
        $this->tournamentsIncluded[$tournamentId] = $originalPoints;
    }

    /**
     * @param int $tournamentsIncludedCount
     */
    public function setTournamentsIncludedCount(int $tournamentsIncludedCount)
    {
        $this->tournamentsIncludedCount = $tournamentsIncludedCount;
    }

    public function increaseTournamentsIncludedCount()
    {
        $this->tournamentsIncludedCount++;
    }

    /**
     * @param int $mastersIncluded
     */
    public function setMastersIncluded(int $mastersIncluded)
    {
        $this->mastersIncluded = $mastersIncluded;
    }

    public function increaseMastersIncluded()
    {
        $this->mastersIncluded++;
    }

    /**
     * @param int $teamMastersIncluded
     */
    public function setTeamMastersIncluded(int $teamMastersIncluded)
    {
        $this->teamMastersIncluded = $teamMastersIncluded;
    }

    public function increaseTeamMastersIncluded()
    {
        $this->teamMastersIncluded++;
    }

    /**
     * @param int $doubleMastersIncluded
     */
    public function setDoubleMastersIncluded(int $doubleMastersIncluded)
    {
        $this->doubleMastersIncluded = $doubleMastersIncluded;
    }

    public function increaseDoubleMastersIncluded()
    {
        $this->doubleMastersIncluded++;
    }

    /**
     * @param int $pointsSum
     */
    public function setPointsSum(int $pointsSum)
    {
        $this->pointsSum = $pointsSum;
    }

    /**
     * @param int $points
     */
    public function addPointsToSum(int $points)
    {
        $this->pointsSum += $points;
    }

    /**
     * @param int $headJudgeBonusReceived
     */
    public function setHeadJudgeBonusReceived(int $headJudgeBonusReceived)
    {
        $this->headJudgeBonusReceived = $headJudgeBonusReceived;
    }


}