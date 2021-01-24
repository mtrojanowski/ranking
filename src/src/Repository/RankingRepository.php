<?php
namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class RankingRepository extends DocumentRepository
{
    public function getRanking(string $seasonId, string $army = "")
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->field("seasonId")->equals($seasonId)
            ->field('army')->equals($army)
            ->sort(['points' => -1, 'tournamentsCount' => -1])
        ->refresh(true);

        $query = $queryBuilder->getQuery();
        $query->setRefresh(true);

        return $query->execute()->toArray();
    }

}
