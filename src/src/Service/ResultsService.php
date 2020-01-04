<?php
namespace App\Service;

use App\Controller\dto\TournamentResults;
use App\Document\Result;
use App\Document\Tournament;
use App\Exception\HeadJudgeBonusException;
use App\Exception\InvalidTournamentException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query;

class ResultsService
{
    private $headJudgePoints = 150;
    private $lineJudgePoints = 100;

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function createTournamentResults(Tournament $tournament, TournamentResults $tournamentResults) : array
    {
        if ($tournament->getRank() === 'local' || $tournament->getRank() === 'league') {
            if ($tournament->getType() === 'single') {
                return $this->createLocalSinglesResults($tournamentResults, $tournament->getSeason(), $tournament->getRank(), $tournament->getType());
            } elseif ($tournament->getType() === 'team' || $tournament->getType() === 'double' ) {
                return $this->createLocalTeamResults($tournamentResults, $tournament->getSeason(), $tournament->getPlayersInTeam(), $tournament->getRank(), $tournament->getType());
            }
        } elseif ($tournament->getRank() === 'master') {
            if ($tournament->getType() === 'single') {
                return $this->createMasterSingleResults($tournamentResults, $tournament->getSeason(), $tournament->getRank(), $tournament->getType(), $tournament->getId());
            } elseif ($tournament->getType() === 'team' || $tournament->getType() === 'double' ) {
                return $this->createMasterTeamResults($tournamentResults, $tournament->getSeason(), $tournament->getPlayersInTeam(), $tournament->getRank(), $tournament->getType(), $tournament->getId());
            }
        }

        throw new InvalidTournamentException();
    }

    private function createMasterSingleResults(TournamentResults $tournamentResults, string $seasonId, string $tournamentRank, string $tournamentType, string $tournamentId) : array
    {
        return $this->createMasterResults($tournamentResults, $seasonId, 1, $tournamentRank, $tournamentType, $tournamentId);
    }

    private function createMasterTeamResults(TournamentResults $tournamentResults, string $seasonId, int $playersInTeam, string $tournamentRank, string $tournamentType, string $tournamentId) : array
    {
        return $this->createMasterResults($tournamentResults, $seasonId, $playersInTeam, $tournamentRank, $tournamentType, $tournamentId);
    }

    private function createMasterResults(TournamentResults $tournamentResults, string $seasonId, int $playersInTeam, string $tournamentRank, string $tournamentType, string $tournamentId) : array
    {
        $results = [];
        $tournamentId = $tournamentResults->getTournamentId();
        $playersInTournament = count(array_filter($tournamentResults->getResults(), function ($results) { return $results->getPlace() > 0; }));

        $multiplier1 = ( $playersInTournament > 24 ? 249 : (10*$playersInTournament - 1) ) / ( $playersInTournament / $playersInTeam - 1 );
        $multiplier2 = ( $playersInTournament > 34 ? 50 : ($playersInTournament > 25 ? (5 * ($playersInTournament - 25)) : 0) );

        foreach ($tournamentResults->getResults() as $tournamentResult) {
            /** @var \App\Controller\dto\Result $tournamentResult */
            $result = new Result();
            $result->setSeasonId($seasonId);
            $result->setTournamentId($tournamentId);
            $result->setPlayerId($tournamentResult->getPlayerId());
            $result->setArmy($tournamentResult->getArmy());
            $result->setPlace($tournamentResult->getPlace());
            $result->setJudge($tournamentResult->getJudge());

            $points = 0;
            if ($tournamentResult->getJudge() == 0) {
                $points = $this->calculatePointsForMaster($playersInTournament, $playersInTeam, $tournamentResult->getPlace(), $multiplier1, $multiplier2);
            } elseif ($tournamentResult->getJudge() == 1) {
                if ($this->playerHasHeadJudgeBonus($tournamentResult->getPlayerId(), $seasonId, $tournamentId)) {
                    throw new HeadJudgeBonusException();
                }
                $points = $this->headJudgePoints;
            }
            elseif ($tournamentResult->getJudge() == 2) {
                $points = $this->lineJudgePoints;
            }
            $result->setPoints($points);

            $result->setTournamentRank($tournamentRank);
            $result->setTournamentType($tournamentType);

            $results[] = $result;
        }

        return $results;
    }

    private function playerHasHeadJudgeBonus($playerId, $seasonId, string $tournamentId): bool
    {
        /** @var Query\Builder $qb */
        $qb = $this->managerRegistry->getManager()->createQueryBuilder(Result::class);
        $qb->field('seasonId')->equals($seasonId)
        ->field('playerId')->equals($playerId)
        ->field('judge')->equals(1)
        ->field('tournamentId')->notEqual($tournamentId);

        $headJudgeBonusesInSeason = $qb->getQuery()->execute();

        return isset($headJudgeBonusesInSeason[0]);
    }

    private function createLocalTeamResults(TournamentResults $tournamentResults, string $seasonId, int $playersInTeam, string $tournamentRank, string $tournamentType) : array
    {
        return $this->createLocalResults($tournamentResults, $seasonId, $playersInTeam, $tournamentRank, $tournamentType);
    }

    private function createLocalSinglesResults(TournamentResults $tournamentResults, string $seasonId, string $tournamentRank, string $tournamentType) : array
    {
        return $this->createLocalResults($tournamentResults, $seasonId,1, $tournamentRank, $tournamentType);
    }

    private function createLocalResults(TournamentResults $tournamentResults, string $seasonId, int $playersInTeam, string $tournamentRank, string $tournamentType) : array
    {
        $results = [];
        $tournamentId = $tournamentResults->getTournamentId();
        $playersInTournament = count($tournamentResults->getResults());
        $multiplier = ($playersInTournament > 9 ? 99 : (10 * $playersInTournament - 1)) / ( $playersInTournament / $playersInTeam - 1);

        foreach ($tournamentResults->getResults() as $tournamentResult) {
            /** @var \App\Controller\dto\Result $tournamentResult */
            $result = new Result();

            $result->setTournamentId($tournamentId);
            $result->setSeasonId($seasonId);
            $result->setPlayerId($tournamentResult->getPlayerId());
            $result->setArmy($tournamentResult->getArmy());
            $result->setPlace($tournamentResult->getPlace());
            $result->setPoints($this->calculatePointsForLocal($playersInTournament, $playersInTeam, $tournamentResult->getPlace(), $multiplier));
            $result->setTournamentRank($tournamentRank);
            $result->setTournamentType($tournamentType);
            $result->setJudge(0);

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