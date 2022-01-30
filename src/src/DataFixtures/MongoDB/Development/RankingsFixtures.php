<?php

namespace App\DataFixtures\MongoDB\Development;

use App\Document\Player;
use App\Document\Ranking;
use App\Document\RankingPlayer;
use App\Document\Season;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;

class RankingsFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $players = PlayersFixtures::$players;

        /** @var Season $previousSeason */
        $previousSeason = $this->getReference(SeasonFixtures::PREVIOUS_SEASON);
        $this->initializeRankingForSeason($previousSeason, $players, $manager);

        /** @var Season $activeSeason */
        $activeSeason = $this->getReference(SeasonFixtures::CURRENT_SEASON);
        $this->initializeRankingForSeason($activeSeason, $players, $manager);

        $manager->flush();
    }

    private function initializeRankingForSeason(Season $season, array $players, ObjectManager $manager) {
        foreach ($players as $playerReference) {
            /** @var Player $player */
            $player = $this->getReference($playerReference);

            $rankingPlayer = new RankingPlayer();
            $rankingPlayer->setFirstName($player->getFirstName());
            $rankingPlayer->setName($player->getName());
            $rankingPlayer->setNickname($player->getNickname());
            $rankingPlayer->setAssociation($player->getAssociation());
            $rankingPlayer->setTown($player->getTown());
            $rankingPlayer->setEmail($player->getEmail());
            $rankingPlayer->setCountry($player->getCountry());

            $ranking = new Ranking();
            $ranking->setPlayerId($player->getLegacyId());
            $ranking->setSeasonId($season->getId());
            $ranking->setPoints(0);
            $ranking->setTournamentCount(0);
            $ranking->setTournamentsIncluded([]);
            $ranking->setPlayer($rankingPlayer);
            $ranking->setArmy("");

            $manager->persist($ranking);
        }
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
            PlayersFixtures::class
        ];
    }


}
