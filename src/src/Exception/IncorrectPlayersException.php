<?php
namespace App\Exception;

class IncorrectPlayersException extends \Exception
{
    public function __construct($missingPlayersIds)
    {
        return parent::__construct('Players with ids: ' . $this->printIds($missingPlayersIds) . ' not found in the database.');
    }

    private function printIds(array $playersId): string
    {
        $result = "";

        foreach ($playersId as $id) {
            $result .= "$id, ";
        }

        return substr($result, 0, -2);
    }
}