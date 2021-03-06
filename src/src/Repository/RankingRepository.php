<?php
namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class RankingRepository extends DocumentRepository
{
    public function getRanking(string $seasonId, string $army = "")
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->field("seasonId")->equals($seasonId)
            ->field('army')->equals($army)
            ->sort(['points' => -1, 'tournamentsCount' => -1]);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }
}
