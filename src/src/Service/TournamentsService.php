<?php
namespace App\Service;

use App\Controller\dto\TournamentResults;
use App\Controller\dto\Result;
use App\Document\Player;
use App\Document\Season;
use App\Document\Tournament;
use App\Exception\IncorrectPlayersException;
use App\Repository\PlayerRepository;
use App\Repository\SeasonRepository;
use Doctrine\Persistence\ManagerRegistry;

class TournamentsService
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function prepareTournament(Tournament $tournament)
    {
        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->managerRegistry->getRepository(Season::class);
        $activeSeason = $seasonRepository->getActiveSeason();

        $tournament->setSeason($activeSeason->getId());
        $tournament->setStatus("NEW");

        $lastId = $this->managerRegistry->getRepository(Tournament::class)->getLastLegacyId();
        $tournament->setLegacyId($lastId + 1);

        return $tournament;
    }

    /**
     * @param TournamentResults $results
     * @throws IncorrectPlayersException
     */
    public function verifyTournamentPlayers(TournamentResults $results)
    {
        $playerIds = [];
        foreach ($results->getResults() as $result) {
            /** @var Result $result */
            $playerIds[] = $result->getPlayerId();
        }

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->managerRegistry->getRepository(Player::class);
        $playersInDb = $playerRepository->getPlayersIds($playerIds);

        if (count($playersInDb) !== count($playerIds)) {

            throw new IncorrectPlayersException(
                $this->findPlayersNotInDb($playerIds, $playersInDb),
                $this->findDuplicatePlayers($playerIds)
            );
        }
    }

    private function findPlayersNotInDb($playerIds, $playersInDb)
    {
        $dbPlayerIds = [];
        foreach ($playersInDb as $player) {
            $dbPlayerIds[] = $player['legacyId'];
        }

        $playersNotInDb = [];
        foreach ($playerIds as $player) {
            if (array_search($player, $dbPlayerIds) === false) {
                $playersNotInDb[] = $player;
            }
        }

        return $playersNotInDb;
    }

    private function findDuplicatePlayers($playerIds)
    {
        $playerIdsStats = [];
        foreach ($playerIds as $playerId) {
            if (!isset($playerIdsStats[$playerId])) {
                $playerIdsStats[$playerId] = 1;
            } else {
                $playerIdsStats[$playerId]++;
            }
        }

        $duplicateIds = [];
        foreach ($playerIdsStats as $playerId => $count) {
            if ($count > 1) {
                $duplicateIds[] = $playerId;
            }
        }

        return $duplicateIds;
    }
}
