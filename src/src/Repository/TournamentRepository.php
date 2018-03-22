<?php
namespace App\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;

class TournamentRepository extends DocumentRepository
{
    public function getTournaments(string $previous)
    {
        $queryBuilder = $this->createQueryBuilder();
        $todayDate = new \DateTime();
        $today = $todayDate->format('Y-m-d');

        if ($previous == 'true') {
            $queryBuilder
                ->field('date')->lt($today);
        } else {
            $queryBuilder
                ->field('date')->gte($today);
        }

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }

    public function getLastLegacyId()
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->sort('legacyId', -1)
            ->limit(1);

        $tournament = $queryBuilder->getQuery()->getSingleResult();

        return !empty($tournament) ? $tournament->getLegacyId() : 0;
    }
}
