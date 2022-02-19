<?php
namespace App\Service;

interface HeadJudgeBonusChecker
{
    public function playerHasHeadJudgeBonus($playerId, $seasonId, string $tournamentId): bool;
}
