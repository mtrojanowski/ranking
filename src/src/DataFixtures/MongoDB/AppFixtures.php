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

        $oldSeason = new Season();
        $oldSeason->setName('Sezon 2017');
        $oldSeason->setActive(false);
        $oldSeason->setEndDate('2017-12-31');
        $oldSeason->setStartDate('2017-01-01');
        $oldSeason->setLimitOfMasterTournaments(4);
        $oldSeason->setLimitOfPairMasterTournaments(1);
        $oldSeason->setLimitOfTeamMasterTournaments(2);
        $oldSeason->setLimitOfTournaments(10);
        $manager->persist($oldSeason);

        $currentSeason = new Season();
        $currentSeason->setName('Sezon obecny');
        $currentSeason->setActive(true);
        $currentSeason->setEndDate('2120-12-31');
        $currentSeason->setStartDate('2020-01-01');
        $currentSeason->setLimitOfMasterTournaments(4);
        $currentSeason->setLimitOfPairMasterTournaments(1);
        $currentSeason->setLimitOfTeamMasterTournaments(2);
        $currentSeason->setLimitOfTournaments(10);
        $manager->persist($currentSeason);

        $manager->flush();
    }
}
