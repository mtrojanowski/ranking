<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Player;
use App\Document\Ranking;
use App\Document\RankingPlayer;
use App\Document\Result;
use App\Document\Season;
use Doctrine\Persistence\ObjectManager;

class RankingFixtures extends TournamentFixture
{
    private $armies = ["VC", "SE", "DL", "EoS"];

    public function load(ObjectManager $manager)
    {
        $players = [];
        for ($i = 0; $i < 10; $i++) {
            $player = $this->createPlayer($i);
            $players[] = $player;
            $manager->persist($player);
        }

        $currentSeason = $this->createActiveSeason();
        $manager->persist($currentSeason);

        // Create previous season
        $previousSeason = FixturesBase::getSeason(false, new \DateTime('2019-01-01'), new \DateTime('2019-12-31'));
        $manager->persist($previousSeason);

        //Add tournaments with results for current season
        $tournamentsIncluded = [];
        for ($t = 0; $t < 4; $t++) {
            $this->createTournamentWithResultsForSeason(120 + $t, $currentSeason, $manager, 10);
            $tournamentsIncluded[] = 1120 + $t;
        }

        $this->createRankingEntriesForSeason($currentSeason, $manager, 10, $players, $tournamentsIncluded);

        //Add tournaments with results for previous season
        $tournamentsIncluded = [];
        for ($t = 0; $t < 4; $t++) {
            $this->createTournamentWithResultsForSeason(220 + $t, $previousSeason, $manager, 7);
            $tournamentsIncluded[] = 1220 + $t;
        }

        $this->createRankingEntriesForSeason($previousSeason, $manager, 7, $players, $tournamentsIncluded);

        $manager->flush();
    }

    private function createTournamentWithResultsForSeason($legacyIdPart, Season $season, ObjectManager $manager, $playersCount)
    {
        $pastDate = new \DateTime();
        $pastDate->sub(\DateInterval::createFromDateString("3 days"));
        $tournament = $this->getTournament($legacyIdPart, $pastDate, $season);
        $tournament->setStatus("OK");
        $manager->persist($tournament);

        for ($i = 0; $i < $playersCount; $i++) {
            $result = new Result();
            $result->setPlayerId($i + 1000);
            $result->setPlace($i + 1);
            $result->setSeasonId($season->getId());
            $result->setTournamentId($tournament->getLegacyId());
            $result->setTournamentRank($tournament->getRank());
            $result->setTournamentType($tournament->getType());
            $result->setPoints(100 - 9*$i);
            $result->setArmy($this->armies[$i % 4]);
            $manager->persist($result);
        }
    }

    private function createRankingEntriesForSeason(Season $season, ObjectManager $manager, int $numberOfPlayers,  $players, $tournamentsIncluded)
    {
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            /** @var Player $player */
            $player = $players[$i];
            $rankingEntry = $this->getRankingEntry($player, "", 1000 - 90 * $i, $season->getId(), $tournamentsIncluded);
            $armyRankingEntry = $this->getRankingEntry($player, $this->armies[$i % 4], 1000 - 90 * $i, $season->getId(), $tournamentsIncluded);

            $manager->persist($rankingEntry);
            $manager->persist($armyRankingEntry);
        }
    }

    private function getRankingEntry(Player $player, string $army, int $points, string $seasonId, $tournamentsIncluded) {
        $rankingEntry = new Ranking();
        $rankingEntry->setPlayerId($player->getLegacyId());
        $rankingEntryPlayer = new RankingPlayer();

        $rankingEntryPlayer->setName($player->getName());
        $rankingEntryPlayer->setAssociation($player->getAssociation());
        $rankingEntryPlayer->setCountry($player->getCountry());
        $rankingEntryPlayer->setEmail($player->getEmail());
        $rankingEntryPlayer->setFirstName($player->getFirstName());
        $rankingEntryPlayer->setNickname($player->getNickname());
        $rankingEntryPlayer->setTown($player->getTown());
        $rankingEntry->setPlayer($rankingEntryPlayer);

        $rankingEntry->setArmy($army);
        $rankingEntry->setHeadJudgeBonusReceived(0);
        $rankingEntry->setPoints($points);
        $rankingEntry->setSeasonId($seasonId);
        $rankingEntry->setTournamentCount(4);
        $rankingEntry->setTournamentsAttendedCount(4);
        $rankingEntry->setTournamentsIncluded($tournamentsIncluded);

        return $rankingEntry;
    }
}
