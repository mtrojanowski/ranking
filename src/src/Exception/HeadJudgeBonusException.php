<?php
namespace App\Exception;

class HeadJudgeBonusException extends \Exception
{
    public function __construct()
    {
        return parent::__construct('Player already received head judge bonus this season');
    }
}