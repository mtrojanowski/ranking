<?php
namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class TournamentRepository extends DocumentRepository
{
    public function getTournaments(string $previous, string $activeSeasonId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $todayDate = new \DateTime();
        $todayDate->setTime(0, 0, 0);

        $queryBuilder
            ->field("season")->equals($activeSeasonId);

        if ($previous == 'true') {
            $queryBuilder
                ->field('date')->lt($todayDate);
        } else {
            $queryBuilder
                ->field('date')->gte($todayDate);
        }

        $queryBuilder->sort("date", 1);

        return $queryBuilder->getQuery()->execute()->toArray();
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

    public function findTournaments(array $tournamentIds)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->field('legacyId')->in($tournamentIds);
        $queryBuilder->sort('date', -1);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }
}
