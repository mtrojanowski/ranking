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
            } elseif ($tournament->getType() === 'team') {
                return $this->createLocalTeamResults($tournamentResults, $tournament->getPlayersInTeam());
            }
        } elseif ($tournament->getRank() === 'master') {
            if ($tournament->getType() === 'single') {
                return $this->createMasterSingleResults($tournamentResults);
            } elseif ($tournament->getType() === 'team') {
                return $this->createMasterTeamResults($tournamentResults, $tournament->getPlayersInTeam());
            }
        }

        throw new InvalidTournamentException();
    }

    private function createMasterSingleResults(TournamentResults $tournamentResults) : array
    {
        return $this->createMasterResults($tournamentResults, 1);
    }

    private function createMasterTeamResults(TournamentResults $tournamentResults, int $playersInTeam) : array
    {
        return $this->createMasterResults($tournamentResults, $playersInTeam);
    }

    private function createMasterResults(TournamentResults $tournamentResults, int $playersInTeam) : array
    {
        $results = [];
        $tournamentId = $tournamentResults->getTournamentId();
        $playersInTournament = count($tournamentResults->getResults());
        $multiplier1 = ( $playersInTournament > 24 ? 249 : (10*$playersInTournament - 1) ) / ( $playersInTournament / $playersInTeam - 1 );
        $multiplier2 = ( $playersInTournament > 34 ? 50 : ($playersInTournament > 25 ? (5 * ($playersInTournament - 25)) : 0) );

        foreach ($tournamentResults->getResults() as $tournamentResult) {
            /** @var \App\Controller\dto\Result $tournamentResult */
            $result = new Result();

            $result->setTournamentId($tournamentId);
            $result->setPlayerId($tournamentResult->getPlayerId());
            $result->setArmy($tournamentResult->getArmy());
            $result->setPlace($tournamentResult->getPlace());
            $result->setPoints($this->calculatePointsForMaster($playersInTournament, $playersInTeam, $tournamentResult->getPlace(), $multiplier1, $multiplier2));

            $results[] = $result;
        }

        return $results;
    }

    private function createLocalTeamResults(TournamentResults $tournamentResults, int $playersInTeam) : array
    {
        return $this->createLocalResults($tournamentResults, $playersInTeam);
    }

    private function createLocalSinglesResults(TournamentResults $tournamentResults) : array
    {
        return $this->createLocalResults($tournamentResults, 1);
    }

    private function createLocalResults(TournamentResults $tournamentResults, int $playersInTeam) : array
    {
        $results = [];
        $tournamentId = $tournamentResults->getTournamentId();
        $playersInTournament = count($tournamentResults->getResults());
        $multiplier = ($playersInTournament > 9 ? 99 : (10 * $playersInTournament - 1)) / ( $playersInTournament / $playersInTeam - 1);

        foreach ($tournamentResults->getResults() as $tournamentResult) {
            /** @var \App\Controller\dto\Result $tournamentResult */
            $result = new Result();

            $result->setTournamentId($tournamentId);
            $result->setPlayerId($tournamentResult->getPlayerId());
            $result->setArmy($tournamentResult->getArmy());
            $result->setPlace($tournamentResult->getPlace());
            $result->setPoints($this->calculatePointsForLocal($playersInTournament, $playersInTeam, $tournamentResult->getPlace(), $multiplier));

            $results[] = $result;
        }

        return $results;
    }

    private function calculatePointsForLocal(int $playersOnTournament, int $playersInTeam, int $place, float $multiplier) : int
    {
        return (int) (floor(($playersOnTournament / $playersInTeam - $place) * $multiplier) + 1);
    }

    private function calculatePointsForMaster(int $playersOnTournament, int $playersInTeam, int $place, float $multiplier1, float $multiplier2) : int
    {
        return (int) floor(
            ( ($playersOnTournament / $playersInTeam - $place) * $multiplier1 )
            + 1
            + ($multiplier2 * (exp( - ($place - 1) / ($playersOnTournament / 10) )))
        );
    }
}