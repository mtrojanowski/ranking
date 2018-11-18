<?php
namespace App\Service;

use App\Controller\dto\TournamentResults;
use App\Controller\dto\Result;
use App\Document\Tournament;
use App\Exception\IncorrectPlayersException;
use App\Repository\PlayerRepository;
use App\Repository\SeasonRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
        $seasonRepository = $this->managerRegistry->getRepository('App:Season');
        $activeSeason = $seasonRepository->getActiveSeason();

        $tournament->setSeason($activeSeason->getId());
        $tournament->setStatus("NEW");

        $lastId = $this->managerRegistry->getRepository('App:Tournament')->getLastLegacyId();
        $tournament->setLegacyId($lastId + 1);

        return $tournament;
    }

    public function verifyTournamentPlayers(TournamentResults $results)
    {
        $playerIds = [];
        foreach ($results->getResults() as $result) {
            /** @var Result $result */
            $playerIds[] = $result->getPlayerId();
        }

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->managerRegistry->getRepository('App:Player');
        $playersInDb = $playerRepository->getPlayersIds($playerIds);

        if (count($playersInDb) !== count($playerIds)) {
            $playersNotInDb = [];
            foreach ($playerIds as $player) {
                if (!array_search($player, $playersInDb)) {
                    $playersNotInDb[] = $player;
                }
            }

            throw new IncorrectPlayersException($playersNotInDb);
        }
    }
}
