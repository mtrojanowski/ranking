<?php


namespace App\Service;


use App\Document\Result;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\Persistence\ManagerRegistry;

class HeadJudgeBonusCheckerService implements HeadJudgeBonusChecker
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function playerHasHeadJudgeBonus($playerId, $seasonId, string $tournamentId): bool
    {
        /** @var Builder $qb */
        $qb = $this->managerRegistry->getManager()->createQueryBuilder();

        $qb->find(Result::class)
            ->field('seasonId')->equals($seasonId)
            ->field('playerId')->equals($playerId)
            ->field('judge')->equals(1)
            ->field('tournamentId')->notEqual($tournamentId);

        $headJudgeBonusesInSeason = $qb->getQuery()->execute()->toArray();

        return isset($headJudgeBonusesInSeason[0]);
    }
}
