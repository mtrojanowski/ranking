<?php
namespace App\Controller\dto;

class TournamentResults
{
    private $tournamentId;
    private $results;

    /**
     * @return mixed
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * @param mixed $tournamentId
     */
    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = $tournamentId;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults(array $results)
    {
        $this->results = $results;
    }
}
