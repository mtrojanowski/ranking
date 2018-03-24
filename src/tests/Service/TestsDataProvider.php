<?php
namespace App\Tests\Service;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;

class TestsDataProvider
{
    public static $tournamentId = "12345";

    public static function getSmallSinglesLocalInputData() : TournamentResults
    {
        return self::getInputData(SmallSinglesLocalDataProvider::$resultsData);
    }

    public static function getSmallSinglesLocalExpectedResults() : array
    {
        return self::getExpectedResults(
            SmallSinglesLocalDataProvider::$resultsData
        );
    }

    private static function getInputData($inputData) : TournamentResults
    {
        $results = [];

        foreach ($inputData as $place => $data) {
            $result = new Result();
            $result->setPlace($place);
            $result->setPlayerId($data[0]);
            $result->setArmy($data[1]);

            $results[] = $result;
        }

        $tournamentData = new TournamentResults();
        $tournamentData->setTournamentId(self::$tournamentId);
        $tournamentData->setResults($results);

        return $tournamentData;
    }

    private static function getExpectedResults($inputData) : array
    {
        $results = [];

        foreach ($inputData as $place => $expectedResult) {
            $newResult = new \App\Document\Result();
            $newResult->setTournamentId(self::$tournamentId);
            $newResult->setPlayerId($expectedResult[0]);
            $newResult->setArmy($expectedResult[1]);
            $newResult->setPlace($place);
            $newResult->setPoints($expectedResult[2]);

            $results[] = $newResult;
        }

        return $results;
    }
}