<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Player;
use App\Document\Result;
use App\Document\Season;
use App\Document\Tournament;
use Doctrine\Persistence\ObjectManager;
use MongoDB\BSON\ObjectId;

class TournamentListFixtures extends TournamentFixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $player = $this->createPlayer($i);
            $manager->persist($player);
        }

        $currentSeason = $this->createActiveSeason();
        $manager->persist($currentSeason);

        // Add previous tournaments
        $pastDate = new \DateTime();
        $pastDate->sub(\DateInterval::createFromDateString("3 days"));

        for ($i = 0; $i < 5; $i++) {
            $tournament = $this->getTournament($i, $pastDate, $currentSeason);
            $manager->persist($tournament);
        }

        // Add future tournaments
        $futureDate = new \DateTime();
        $futureDate->add(\DateInterval::createFromDateString("3 days"));

        for ($i = 10; $i < 15; $i++) {
            $tournament = $this->getTournament($i, $futureDate, $currentSeason);
            $manager->persist($tournament);
        }

        //Add tournament for results
        $tournament = $this->getTournament(123, $pastDate, $currentSeason);
        $tournament->setStatus("OK");
        $tournament->setId(new ObjectId("5fca99fd752742d853ccfd23"));
        $manager->persist($tournament);

        for ($i = 0; $i < 10; $i++) {
            $result = new Result();
            $result->setPlayerId($i + 1000);
            $result->setPlace($i + 1);
            $result->setSeasonId($currentSeason->getId());
            $result->setTournamentId($tournament->getLegacyId());
            $result->setTournamentRank($tournament->getRank());
            $result->setTournamentType($tournament->getType());
            $result->setPoints(100 - 9*$i);
            $manager->persist($result);
        }

        $manager->flush();
    }
}
