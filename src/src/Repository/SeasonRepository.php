<?php
namespace App\Repository;

use App\Document\Season;
use App\Exception\NoActiveSeasonException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SeasonRepository extends DocumentRepository
{
    public function getActiveSeason() : Season
    {
        $season = $this->createQueryBuilder()
            ->field('active')->equals(true)
            ->getQuery()
            ->getSingleResult();

        if (!$season) {
            throw new NoActiveSeasonException();
        }

        return $season;
    }
}
