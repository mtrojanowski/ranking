<?php
namespace App\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;

class TournamentRepository extends DocumentRepository
{
    public function getTournaments(bool $previous)
    {
        $queryBuilder = $this->createQueryBuilder();
        $today = new \DateTime();

        if ($previous) {
            $queryBuilder
                ->field('date')->lt($today);
        } else {
            $queryBuilder
                ->field('date')->gte($today);
        }

        return $queryBuilder->getQuery()->execute();
    }
}
