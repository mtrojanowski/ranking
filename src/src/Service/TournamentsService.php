<?php
namespace App\Service;

use App\Document\Tournament;
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

        $lastId = $this->managerRegistry->getRepository('App:Tournament')->getLastLegacyId();
        $tournament->setLegacyId($lastId + 1);

        return $tournament;
    }
}
