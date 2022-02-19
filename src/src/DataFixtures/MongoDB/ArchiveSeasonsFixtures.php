<?php
namespace App\DataFixtures\MongoDB;

use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArchiveSeasonsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $oldSeason = FixturesBase::getSeason(
            false,
            new \DateTime("2018-01-01"),
            new \DateTime("2018-12-31")
        );
        $manager->persist($oldSeason);

        $evenOlderSeason = FixturesBase::getSeason(
            false,
            new \DateTime("2017-01-01"),
            new \DateTime("2017-12-31")
        );
        $manager->persist($evenOlderSeason);

        $currentSeason = FixturesBase::getSeason(
            true,
            new \DateTime("first day of January this year")
        );
        $manager->persist($currentSeason);

        $manager->flush();
    }
}
