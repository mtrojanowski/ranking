<?php
namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class PlayerRepository extends DocumentRepository
{
    function getPlayersIds(array $playerIds) {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(['legacyId'])
            ->field('legacyId')
            ->in($playerIds);

        return $queryBuilder->hydrate(false)->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }

    function getPlayersByIds(array $playerIds) {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select()
            ->field('legacyId')
            ->in($playerIds);

        return $queryBuilder->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();
    }

    function getLatestLegacyId(): string {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(['legacyId'])
            ->sort("legacyId", -1)
            ->limit(1);

        $result = $queryBuilder->hydrate(false)->getQuery()->execute()->current();

        if (is_array($result)) {
            return $result["legacyId"];
        }

        return "0";
    }
}
