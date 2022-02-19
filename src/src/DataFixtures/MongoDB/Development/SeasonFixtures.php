<?php
namespace App\DataFixtures\MongoDB\Development;

use App\DataFixtures\MongoDB\FixturesBase;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public const PREVIOUS_SEASON = "previous-season";
    public const CURRENT_SEASON = "current-season";

    public function load(ObjectManager $manager)
    {
        $oldSeason = FixturesBase::getSeason(
            false,

            new \DateTime("first day of January previous year"),
            new \DateTime("last day of December previous year")
        );
        $manager->persist($oldSeason);
        $this->setReference(self::PREVIOUS_SEASON, $oldSeason);

        $olderSeason = FixturesBase::getSeason(
            false,

            new \DateTime("-2 year"),
            new \DateTime("-2 year")
        );
        $manager->persist($olderSeason);

        $currentSeason = FixturesBase::getSeason(
            true,
            new \DateTime("first day of January this year")
        );
        $manager->persist($currentSeason);
        $this->setReference(self::CURRENT_SEASON, $currentSeason);

        $manager->flush();
    }

}
