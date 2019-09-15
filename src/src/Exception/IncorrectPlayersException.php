<?php
namespace App\Exception;

class IncorrectPlayersException extends \Exception
{
    public function __construct($missingPlayersIds, $duplicatePlayerIds)
    {
        if (isset($missingPlayersIds[0])) {
            $missingText =  'Players with ids: ' . join(", ", $missingPlayersIds) . ' not found in the database.';
        } else {
            $missingText = '';
        }

        if (isset($duplicatePlayerIds[0])) {
            $duplicateText = 'Players with ids: ' . join(", ", $duplicatePlayerIds) . ' appear more than once in the results.';
        } else {
            $duplicateText = '';
        }


        return parent::__construct($missingText . ' ' . $duplicateText);
    }
}
