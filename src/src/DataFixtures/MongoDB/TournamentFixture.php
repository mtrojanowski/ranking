<?php


namespace App\DataFixtures\MongoDB;


use App\Document\Player;
use App\Document\Season;
use App\Document\Tournament;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;

abstract class TournamentFixture extends Fixture
{
    protected function getTournament(int $i, \DateTime $date, Season $currentSeason): Tournament
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

    protected function createPlayer($randomPart)
    {
            $player = new Player();
            $player->setLegacyId($randomPart + 1000);
            $player->setFirstName('Player' . $randomPart);
            $player->setName('Name' . $randomPart);
            $player->setCountry('PL');
            $player->setAssociation('Club' . $randomPart);
            $player->setNickname('alias' . $randomPart);
            $player->setTown('Town' . $randomPart);

            return $player;
    }

    protected function createActiveSeason(): Season
    {
        return FixturesBase::getSeason(true, new \DateTime('2020-01-01'), new \DateTime('2120-12-31'));
    }
}
