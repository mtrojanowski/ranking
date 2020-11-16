<?php


namespace App\Service;


use App\Document\Result;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\MongoDB\Query\Builder;

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
        $qb = $this->managerRegistry->getManager()->createQueryBuilder(Result::class);
        $qb->field('seasonId')->equals($seasonId)
            ->field('playerId')->equals($playerId)
            ->field('judge')->equals(1)
            ->field('tournamentId')->notEqual($tournamentId);

        $headJudgeBonusesInSeason = $qb->getQuery()->execute()->setUseIdentifierKeys(false)->toArray();

        return isset($headJudgeBonusesInSeason[0]);
    }
}
