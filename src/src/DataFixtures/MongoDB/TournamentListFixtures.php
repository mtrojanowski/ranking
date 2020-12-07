<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Player;
use App\Document\Result;
use App\Document\Season;
use App\Document\Tournament;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;
use MongoDB\BSON\ObjectId;

class TournamentListFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $player = new Player();
            $player->setLegacyId($i + 1000);
            $player->setFirstName('Player' . $i);
            $player->setName('Name' . $i);
            $player->setCountry('PL');
            $player->setAssociation('Club' . $i);
            $player->setNickname('alias' . $i);
            $player->setTown('Town' . $i);
            $manager->persist($player);
        }

        $currentSeason = new Season();
        $currentSeason->setName('Current season');
        $currentSeason->setActive(true);
        $currentSeason->setEndDate('2120-12-31');
        $currentSeason->setStartDate('2020-01-01');
        $currentSeason->setLimitOfMasterTournaments(4);
        $currentSeason->setLimitOfPairMasterTournaments(1);
        $currentSeason->setLimitOfTeamMasterTournaments(2);
        $currentSeason->setLimitOfTournaments(10);
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

    private function getTournament(int $i, \DateTime $date, Season $currentSeason): Tournament
    {
        $tournament = new Tournament();
        $tournament->setLegacyId(1000 + $i);
        $tournament->setName("Tournament$i");
        $tournament->setTown('ATown');
        $tournament->setDate($date);
        $tournament->setOrganiser("Org");
        $tournament->setPlayersInTeam(1);
        $tournament->setPoints(4500);
        $tournament->setRank('local');
        $tournament->setSeason($currentSeason->getId());
        $tournament->setType('single');
        $tournament->setStatus('NEW');
        $tournament->setVenue("A venue");

        return $tournament;
    }
}
