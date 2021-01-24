<?php

namespace App\DataFixtures\MongoDB;

use Doctrine\Persistence\ObjectManager;
use MongoDB\BSON\ObjectId;

class TournamentResultsFixtures extends TournamentFixture
{
    public const TOURNAMENT_ID = "5fca99fd752742d853ccfd23";
    public const TOURNAMENT_LEGACY_ID = 1123;
    public const INVALID_TYPE_TOURNAMENT_ID = 1234;
    public const DIFFERENT_TOURNAMENT_ID = 2345;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $player = $this->createPlayer($i);
            $manager->persist($player);
        }

        $currentSeason = $this->createActiveSeason();
        $manager->persist($currentSeason);

        //Add tournament for results
        $tournament = $this->getTournament(0, new \DateTime("2020-12-01"), $currentSeason);
        $tournament->setStatus("NEW");
        $tournament->setLegacyId(self::TOURNAMENT_LEGACY_ID);
        $tournament->setId(new ObjectId(self::TOURNAMENT_ID));
        $manager->persist($tournament);

        //Add a different tournament for results
        $tournament = $this->getTournament(0, new \DateTime("2020-12-01"), $currentSeason);
        $tournament->setStatus("NEW");
        $tournament->setLegacyId(self::DIFFERENT_TOURNAMENT_ID);
        $manager->persist($tournament);


        //Add tournament with invalid type
        $tournament = $this->getTournament(0, new \DateTime("2020-12-01"), $currentSeason);
        $tournament->setStatus("NEW");
        $tournament->setLegacyId(self::INVALID_TYPE_TOURNAMENT_ID);
        $tournament->setType('tripler');
        $manager->persist($tournament);

        $manager->flush();
    }


}
