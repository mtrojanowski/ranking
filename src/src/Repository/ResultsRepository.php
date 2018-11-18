<?php
namespace App\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;

class ResultsRepository extends DocumentRepository
{
    public function getPlayersResults(string $playerId, string $seasonId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->find()
            ->field('playerId')->equals($playerId)
            ->field('seasonId')->equals($seasonId)
            ->sort(['points' => -1, 'tournamentRank' => -1]);

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
}
