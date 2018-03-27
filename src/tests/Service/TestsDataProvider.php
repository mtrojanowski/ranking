<?php
namespace App\Tests\Service;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;

class TestsDataProvider
{
    public static $tournamentId = "12345";
    private static $armies = ['VC', 'DH', 'EoS', 'SE', 'SA', 'VS', 'WDG', 'DL', 'HbE', 'DE', 'ID', 'OK', 'OG', 'KoE', 'UD'];

    private static $smallSingleLocalPoints = [ 70, 58, 47, 35, 24, 12, 1 ];
    private static $largeSingleLocalPoints = [ 100, 96, 92, 89, 85, 82, 78, 75, 71, 68, 64, 61, 57, 54, 50, 46, 43, 39, 36, 32, 29, 25, 22, 18, 15, 11, 8, 4, 1 ];

    public static function getSmallSinglesLocalTestData() : array
    {
        return self::getTestData(
            self::getResultsData(self::$smallSingleLocalPoints)
        );
    }

    public static function getLargeSinglesLocalTestData() : array
    {
        return self::getTestData(
            self::getResultsData(self::$largeSingleLocalPoints)
        );
    }


    private static function getTestData(array $data) : array
    {
        return [
            'input' => self::getInputData($data),
            'expectedResult' => self::getExpectedResults($data)
        ];
    }

    private static function getNextPlayerId()
    {
        try {
            return (string) random_int(1240, 6543);
        } catch (\Exception $e) {
            return '3456';
        }
    }

    private static function getResultsData(array $points)
    {
        $resultsData = [];

        foreach ($points as $point) {
            $armyKey = array_rand(self::$armies);
            $resultsData[] = [
                self::getNextPlayerId(),
                self::$armies[$armyKey],
                $point
            ];
        }

        return $resultsData;
    }

    private static function getInputData($inputData) : TournamentResults
    {
        $results = [];

        foreach ($inputData as $i => $data) {
            $result = new Result();
            $result->setPlace($i + 1);
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

        foreach ($inputData as $i => $expectedResult) {
            $newResult = new \App\Document\Result();
            $newResult->setTournamentId(self::$tournamentId);
            $newResult->setPlayerId($expectedResult[0]);
            $newResult->setArmy($expectedResult[1]);
            $newResult->setPlace($i + 1);
            $newResult->setPoints($expectedResult[2]);

            $results[] = $newResult;
        }

        return $results;
    }
}
