<?php
namespace App\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;

class ResultsRepository extends DocumentRepository
{
    public function getPlayersResults(string $playerId, string $seasonId)
    {
        $queryBuilder = $this->getPlayersResultsQuery($playerId, $seasonId);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }

    public function getPlayersResultsForArmy(string $playerId, string $seasonId, string $army)
    {
        $queryBuilder = $this
            ->getPlayersResultsQuery($playerId, $seasonId)
            ->field('army')->equals($army);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }

    public function deleteTournamentResults(string $tournamentId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->remove()
            ->field('tournamentId')->equals($tournamentId);

        return $queryBuilder->getQuery()->execute();
    }

    private function getPlayersResultsQuery(string $playerId, string $seasonId): Builder
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->find()
            ->field('playerId')->equals($playerId)
            ->field('seasonId')->equals($seasonId)
            ->sort(['points' => -1, 'tournamentRank' => -1]);
    }
}
