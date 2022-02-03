<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Season;

class FixturesBase
{
    public static function getSeason(bool $isActive, \DateTime $startOfSeason, \DateTime $endOfSeason = null): Season {
        $season = new Season();
        $season->setActive($isActive);
        $season->setStartDate($startOfSeason);
        $season->setName($startOfSeason->format("Y"));
        $season->setLimitOfMasterTournaments(4);
        $season->setLimitOfPairMasterTournaments(1);
        $season->setLimitOfTeamMasterTournaments(2);
        $season->setLimitOfTournaments(10);
        $season->setRankingLastModified($startOfSeason);

        if ($endOfSeason) {
            $season->setEndDate($endOfSeason);
        }

        return $season;
    }
}
