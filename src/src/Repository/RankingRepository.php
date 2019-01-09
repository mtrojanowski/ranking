<?php
namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class RankingRepository extends DocumentRepository
{
    const ACTIVE_SEASON_ID = "5c36642979dab7965c7e5d23";

    public function getRanking()
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->field("seasonId")->equals(self::ACTIVE_SEASON_ID)
            ->sort(['points' => -1, 'tournamentsCount' => -1]);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }
}
