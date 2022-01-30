<?php
namespace App\DataFixtures\MongoDB\Development;

use App\Document\Season;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public const PREVIOUS_SEASON = "previous-season";
    public const CURRENT_SEASON = "current-season";

    public function load(ObjectManager $manager)
    {
        $oldSeason = $this->getSeason(
            false,

            new \DateTime("first day of January previous year"),
            new \DateTime("last day of December previous year")
        );
        $manager->persist($oldSeason);
        $this->setReference(self::PREVIOUS_SEASON, $oldSeason);

        $currentSeason = $this->getSeason(
            true,
            new \DateTime("first day of January this year")
        );
        $manager->persist($currentSeason);
        $this->setReference(self::CURRENT_SEASON, $currentSeason);

        $manager->flush();
    }

    private function getSeason(bool $isActive, \DateTime $startOfSeason, \DateTime $endOfSeason = null): Season {
        $season = new Season();
        $season->setActive($isActive);
        $season->setStartDate($startOfSeason);
        $season->setName($startOfSeason->format("Y"));
        $season->setLimitOfMasterTournaments(4);
        $season->setLimitOfPairMasterTournaments(1);
        $season->setLimitOfTeamMasterTournaments(2);
        $season->setLimitOfTournaments(10);

        if ($endOfSeason) {
            $season->setEndDate($endOfSeason);
        }

        return $season;
    }

}
