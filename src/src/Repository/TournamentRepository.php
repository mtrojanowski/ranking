<?php
namespace App\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;

class TournamentRepository extends DocumentRepository
{
    const ACTIVE_SEASON_ID = "5c36642979dab7965c7e5d23";

    public function getTournaments(string $previous)
    {
        $queryBuilder = $this->createQueryBuilder();
        $todayDate = new \DateTime();
        $today = new \MongoDate($todayDate->setTime(0, 0, 0)->getTimestamp());

        $queryBuilder
            ->field("season")->equals(self::ACTIVE_SEASON_ID);

        if ($previous == 'true') {
            $queryBuilder
                ->field('date')->lt($today);
        } else {
            $queryBuilder
                ->field('date')->gte($today);
        }

        $queryBuilder->sort("date", 1);

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

    public function findTournaments(array $tournamentIds)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->field('legacyId')->in($tournamentIds);
        $queryBuilder->sort('date', -1);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }
}
