<?php
namespace App\Service;


use App\Document\Ranking;
use Doctrine\Common\Persistence\ManagerRegistry;

class RankingService
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function recalculateRanking(Ranking $currentRanking) : Ranking
    {

    }

    public function createInitialRanking($playerId) : Ranking
    {

    }
}