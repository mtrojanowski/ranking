<?php
namespace App\Service;

use App\Controller\dto\TournamentResults;
use App\Document\Result;
use App\Document\Tournament;
use App\Exception\InvalidTournamentException;

class ResultsService
{
    public function createTournamentResults(Tournament $tournament, TournamentResults $tournamentResults) : array
    {
        if ($tournament->getRank() === 'local') {
            if ($tournament->getType() === 'single') {
                return $this->createLocalSinglesResults($tournamentResults);
            }
        }

        throw new InvalidTournamentException();
    }

    private function createLocalSinglesResults(TournamentResults $tournamentResults) : array
    {
        $results = [];
        $tournamentId = $tournamentResults->getTournamentId();
        $playersInTournament = count($tournamentResults->getResults());
        $multiplier = ($playersInTournament > 9 ? 99 : (10 * $playersInTournament - 1)) / ( $playersInTournament / 1 - 1);

        foreach ($tournamentResults->getResults() as $tournamentResult) {
            /** @var \App\Controller\dto\Result $tournamentResult */
            $result = new Result();

            $result->setTournamentId($tournamentId);
            $result->setPlayerId($tournamentResult->getPlayerId());
            $result->setArmy($tournamentResult->getArmy());
            $result->setPlace($tournamentResult->getPlace());
            $result->setPoints($this->calculatePointsForLocal($playersInTournament, 1, $tournamentResult->getPlace(), $multiplier));

            $results[] = $result;
        }

        return $results;
    }

    private function calculatePointsForLocal(int $playersOnTournament, int $playersInTeam, int $place, float $multiplier) : int
    {
        return (int) (floor(($playersOnTournament / $playersInTeam - $place) * $multiplier) + 1);
    }
}