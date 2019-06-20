<?php
namespace App\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;

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
}
