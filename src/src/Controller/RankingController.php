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
use Doctrine\ODM\MongoDB\DocumentManager;
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

    public function list(Request $request, DocumentManager $dm, string $seasonId = null) {
        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $dm->getRepository('App:Ranking');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository('App:Season');

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

        $modificationDate = $season->getRankingLastModified();
        $rankingData = new RankingDataDto($ranking, $modificationDate, $this->generateRankingTitle($season, $army));

        $headers = self::CACHE_FOR_A_MINUTE;
        $headers['Last-Modified'] = $modificationDate->format("D, d M Y H:i:s") . "GMT";

        return $this->json($this->getSerializer()->normalize($rankingData, 'json'), 200, $headers);
    }

    public function individual(Request $request, DocumentManager $dm, string $playerId, string $seasonId = null) {
        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository('App:Season');

        if (!$seasonId) {
            // Try to get seasonId from Query
            $seasonId = $request->get('seasonId');

            if (!$seasonId) {
                // Get active season by default
                $season = $seasonRepository->getActiveSeason();
                $seasonId = $season->getId();
            }
        }

        $playersResults = $dm->getRepository('App:Result')
            ->findBy(['seasonId' => $seasonId, 'playerId' => $playerId]);
        /** @var Ranking $rankingData */
        $rankingData = $dm->getRepository('App:Ranking')
            ->findOneBy(['seasonId' => $seasonId, 'playerId' => $playerId]);

        $tournamentIds = [];
        $resultsByTournament = [];

        foreach ($playersResults as $result) {
            /** @var Result $result */
            $tournamentIds[] = (int) $result->getTournamentId();
            $resultsByTournament[$result->getTournamentId()] = $result;
        }

        $tournaments = $dm->getRepository('App:Tournament')
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
                $includedTournaments[$tournament->getLegacyId()] ?? 0,
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

        return $this->json($this->getSerializer()->normalize($individualRanking, 'json'), 200, self::CACHE_FOR_A_MINUTE);
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
