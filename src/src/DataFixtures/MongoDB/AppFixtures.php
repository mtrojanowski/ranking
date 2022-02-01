<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Player;
use App\Document\Season;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends TournamentFixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $player = $this->createPlayer($i);
            $manager->persist($player);
        }

        $oldSeason = FixturesBase::getSeason(
            false, new \DateTime('2017-01-01'), new \DateTime('2017-12-31')
        );
        $manager->persist($oldSeason);

        $currentSeason = FixturesBase::getSeason(
            true, new \DateTime('2020-01-01'), new \DateTime('2120-12-31')
        );
        $manager->persist($currentSeason);

        $manager->flush();
    }
}
