<?php
namespace App\Exception;

class InvalidTournamentException extends \Exception
{
    public function __construct()
    {
        return parent::__construct("Unsupported type or rank of tournament");
    }
}