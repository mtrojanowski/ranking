<?php
namespace App\Controller;

use App\Controller\dto\IndividualRankingDto;
use App\Controller\dto\IndividualRankingTournamentDto;
use App\Controller\dto\RankingDataDto;
use App\Controller\dto\RankingDto;
use App\Controller\dto\RankingPlayerDto;
use App\Document\Ranking;
use App\Document\Result;
use App\Document\Season;
use App\Document\Tournament;
use App\Repository\RankingRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Request;

class RankingController extends AppController
{

    private static $armies = [
        "UD" => "Undying Dynasties",
        "WDG" => "Warriors of the Dark Gods",
        "DE" => "Dread Elves",
        "EOS" => "Empire of Sonnstahl",
        "KOE" => "Kingdom of Equitaine",
        "VC" => "Vampire Covenant",
        "VS" => "The Vermin Swarm",
        "SE" => "Sylvan Elves",
        "HE" => "Highborn Elves",
        "OG" => "Orcs and Goblins",
        "BH" => "Beast Herds",
        "DH" => "Dwarven Holds",
        "OK" => "Ogre Khans",
        "DL" => "Daemonic Legion",
        "SA" => "Saurian Ancients",
        "ID" => "Infernal Dwrves",
    ];

    public function list(Request $request, string $seasonId = null) {
        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $this->getMongo()
            ->getRepository('App:Ranking');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->getMongo()->getRepository('App:Season');

        if (!$seasonId) {
            $season = $seasonRepository->getActiveSeason();
            $seasonId = $season->getId();
        } else {
            $season = $seasonRepository->find($seasonId);
        }

        $army = $request->get('army') ?: "";
        $players = $rankingRepository->getRanking($seasonId, $army);
        $ranking = [];

        foreach ($players as $player) {
            /** @var Ranking $player */
            $playerData = $player->getPlayer();
            $ranking[] = new RankingDto(
                $player->getId(),
                new RankingPlayerDto(
                    $player->getPlayerId(),
                    $playerData->getFirstName(),
                    $playerData->getNickname(),
                    $playerData->getTown(),
                    $playerData->getCountry(),
                    $playerData->getAssociation()
                ),
                $player->getPoints(),
                $player->getTournamentCount(),
                $player->getTournamentsIncluded(),
                $player->getTournamentsAttendedCount()
            );
        }

        $modificationDate = $season->getRankingLastModified() != null ? new \DateTime('@'.$season->getRankingLastModified()) : null;
        $rankingData = new RankingDataDto($ranking, $modificationDate, $this->generateRankingTitle($season, $army));

        return $this->json($this->getSerializer()->normalize($rankingData, 'json'));
    }

    public function individual($seasonId, $playerId) {
        $playersResults = $this->getMongo()->getRepository('App:Result')
            ->findBy(['seasonId' => $seasonId, 'playerId' => $playerId]);
        /** @var Ranking $rankingData */
        $rankingData = $this->getMongo()->getRepository('App:Ranking')
            ->findOneBy(['seasonId' => $seasonId, 'playerId' => $playerId]);

        $tournamentIds = [];
        $resultsByTournament = [];

        foreach ($playersResults as $result) {
            /** @var Result $result */
            $tournamentIds[] = (int) $result->getTournamentId();
            $resultsByTournament[$result->getTournamentId()] = $result;
        }

        $tournaments = $this->getMongo()->getRepository('App:Tournament')
            ->findTournaments($tournamentIds);

        $individualTournaments = [];
        $includedTournaments = $rankingData->getTournamentsIncluded();

        foreach ($tournaments as $tournament) {
            /** @var Tournament $tournament */
            /** @var Result $result */
            $result = $resultsByTournament[$tournament->getLegacyId()];
            $individualTournaments[] = new IndividualRankingTournamentDto(
                $tournament->getId(),
                (int) $tournament->getLegacyId(),
                $tournament->getDate()->format('d.m.Y'),
                $tournament->getName(),
                $tournament->getRank(),
                $tournament->getType(),
                $tournament->getPlayersInTeam(),
                $result->getPlace(),
                $result->getPoints(),
                $result->getArmy(),
                isset($includedTournaments[$tournament->getLegacyId()]),
                isset($includedTournaments[$tournament->getLegacyId()]) ? $includedTournaments[$tournament->getLegacyId()] : 0,
                $result->getJudge() ?: 0
            );
        }

        $playerData = $rankingData->getPlayer();
        $individualRanking = new IndividualRankingDto(
            $rankingData->getPoints(),
            new RankingPlayerDto(
                $rankingData->getPlayerId(),
                $playerData->getFirstName(),
                $playerData->getNickname(),
                $playerData->getTown(),
                $playerData->getCountry(),
                $playerData->getAssociation()
            ),
            $individualTournaments
        );

        return $this->json($this->getSerializer()->normalize($individualRanking, 'json'));
    }

    private function generateRankingTitle(Season $season, string $army): string {
        $title = "Ranking ";

        if ($army == "") {
            $title .= "generalny";
        } else {
            $title .= "armijny - ".self::$armies[$army];
        }

        if (!$season->getActive()) {
            $title .= ", sezon ".$season->getName();
        }

        return $title;
    }
}