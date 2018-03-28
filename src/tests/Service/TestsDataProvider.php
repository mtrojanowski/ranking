<?php
namespace App\Tests\Service;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;

class TestsDataProvider
{
    public static $tournamentId = "12345";
    private static $armies = ['VC', 'DH', 'EoS', 'SE', 'SA', 'VS', 'WDG', 'DL', 'HbE', 'DE', 'ID', 'OK', 'OG', 'KoE', 'UD'];

    private static $tournaments = [
        'smallSingleLocal' => [ 70, 58, 47, 35, 24, 12, 1 ],
        'largeSingleLocal' => [ 100, 96, 92, 89, 85, 82, 78, 75, 71, 68, 64, 61, 57, 54, 50, 46, 43, 39, 36, 32, 29, 25, 22, 18, 15, 11, 8, 4, 1 ],

        'veryLargeSingleLocal' => [100, 98, 97, 96, 95, 94, 92, 91, 90, 89, 88, 87, 85, 84, 83, 82, 81, 79, 78, 77, 76, 75, 74, 72, 71, 70, 69, 68, 67, 65, 64, 63, 62, 61, 59, 58, 57, 56, 55, 54, 52, 51, 50, 49, 48, 46, 45, 44, 43, 42, 41, 39, 38, 37, 36, 35, 34, 32, 31, 30, 29, 28, 26, 25, 24, 23, 22, 21, 19, 18, 17, 16, 15, 13, 12, 11, 10, 9, 8, 6, 5, 4, 3, 2, 1],

        'smallThreePlayerTeamLocal' => [60, 60, 60, 1, 1, 1],
        'largeThreePlayerTeamLocal' => [100, 100, 100, 91, 91, 91, 82, 82, 82, 73, 73, 73, 64, 64, 64, 55, 55, 55, 46, 46, 46, 37, 37, 37, 28, 28, 28, 19, 19, 19, 10, 10, 10, 1, 1, 1],

        'largeFivePlayerTeamLocal' => [100, 100, 100, 100, 100, 85, 85, 85, 85, 85, 71, 71, 71, 71, 71, 57, 57, 57, 57, 57, 43, 43, 43, 43, 43, 29, 29, 29, 29, 29, 15, 15, 15, 15, 15, 1, 1, 1, 1, 1],

        'smallSingleMaster' => [190, 179, 169, 158, 148, 137, 127, 116, 106, 95, 85, 74, 64, 53, 43, 32, 22, 11, 1],
        'largeSingleMaster' => [285, 267, 252, 239, 227, 217, 207, 197, 188, 179, 171, 162, 154, 146, 137, 129, 121, 113, 105, 97, 89, 81, 73, 65, 57, 49, 41, 33, 25, 17, 9, 1],
        'veryLargeSingleMaster' => [300, 294, 289, 284, 280, 276, 271, 267, 263, 260, 256, 253, 249, 246, 243, 240, 237, 234, 231, 228, 226, 223, 220, 218, 216, 213, 211, 208, 206, 204, 202, 199, 197, 195, 193, 191, 189, 187, 185, 183, 181, 179, 177, 175, 173, 171, 169, 167, 165, 163, 161, 159, 158, 156, 154, 152, 150, 148, 146, 145, 143, 141, 139, 137, 135, 134, 132, 130, 128, 126, 124, 123, 121, 119, 117, 115, 114, 112, 110, 108, 106, 105, 103, 101, 99, 97, 96, 94, 92, 90, 88, 87, 85, 83, 81, 79, 78, 76, 74, 72, 70, 69, 67, 65, 63, 61, 60, 58, 56, 54, 52, 51, 49, 47, 45, 44, 42, 40, 38, 36, 35, 33, 31, 29, 27, 26, 24, 22, 20, 18, 17, 15, 13, 11, 9, 8, 6, 4, 2, 1],
        
        'smallThreePlayerTeamMaster' => [240, 240, 240, 205, 205, 205, 171, 171, 171, 137, 137, 137, 103, 103, 103, 69, 69, 69, 35, 35, 35, 1, 1, 1],
        'largeThreePlayerTeamMaster' => [290, 290, 290, 254, 254, 254, 222, 222, 222, 191, 191, 191, 162, 162, 162, 134, 134, 134, 107, 107, 107, 80, 80, 80, 54, 54, 54, 28, 28, 28, 2, 2, 2],
        'veryLargeThreePlayerTeamMaster' => [300, 300, 300, 293, 293, 293, 287, 287, 287, 281, 281, 281, 275, 275, 275, 269, 269, 269, 263, 263, 263, 257, 257, 257, 252, 252, 252, 246, 246, 246, 241, 241, 241, 235, 235, 235, 230, 230, 230, 225, 225, 225, 219, 219, 219, 214, 214, 214, 209, 209, 209, 204, 204, 204, 199, 199, 199, 194, 194, 194, 190, 190, 190, 185, 185, 185, 180, 180, 180, 175, 175, 175, 171, 171, 171, 166, 166, 166, 162, 162, 162, 157, 157, 157, 152, 152, 152, 148, 148, 148, 144, 144, 144, 139, 139, 139, 135, 135, 135, 130, 130, 130, 126, 126, 126, 122, 122, 122, 117, 117, 117, 113, 113, 113, 109, 109, 109, 105, 105, 105, 100, 100, 100, 96, 96, 96, 92, 92, 92, 88, 88, 88, 84, 84, 84, 79, 79, 79, 75, 75, 75, 71, 71, 71, 67, 67, 67, 63, 63, 63, 59, 59, 59, 55, 55, 55, 51, 51, 51, 47, 47, 47, 43, 43, 43, 38, 38, 38, 34, 34, 34, 30, 30, 30, 26, 26, 26, 22, 22, 22, 18, 18, 18, 14, 14, 14, 10, 10, 10, 6, 6, 6, 2, 2, 2],
        
        'smallFivePlayerTeamMaster' => [200, 200, 200, 200, 200, 133, 133, 133, 133, 133, 67, 67, 67, 67, 67, 1, 1, 1, 1, 1],
        'largeFivePlayerTeamMaster' => [275, 275, 275, 275, 275, 218, 218, 218, 218, 218, 163, 163, 163, 163, 163, 109, 109, 109, 109, 109, 57, 57, 57, 57, 57, 5, 5, 5, 5, 5],
        'veryLargeFivePlayerTeamMaster' => [300, 300, 300, 300, 300, 291, 291, 291, 291, 291, 282, 282, 282, 282, 282, 273, 273, 273, 273, 273, 265, 265, 265, 265, 265, 257, 257, 257, 257, 257, 248, 248, 248, 248, 248, 240, 240, 240, 240, 240, 232, 232, 232, 232, 232, 224, 224, 224, 224, 224, 216, 216, 216, 216, 216, 208, 208, 208, 208, 208, 200, 200, 200, 200, 200, 193, 193, 193, 193, 193, 185, 185, 185, 185, 185, 177, 177, 177, 177, 177, 170, 170, 170, 170, 170, 162, 162, 162, 162, 162, 155, 155, 155, 155, 155, 148, 148, 148, 148, 148, 140, 140, 140, 140, 140, 133, 133, 133, 133, 133, 126, 126, 126, 126, 126, 118, 118, 118, 118, 118, 111, 111, 111, 111, 111, 104, 104, 104, 104, 104, 97, 97, 97, 97, 97, 90, 90, 90, 90, 90, 83, 83, 83, 83, 83, 76, 76, 76, 76, 76, 69, 69, 69, 69, 69, 62, 62, 62, 62, 62, 55, 55, 55, 55, 55, 48, 48, 48, 48, 48, 42, 42, 42, 42, 42, 35, 35, 35, 35, 35, 28, 28, 28, 28, 28, 21, 21, 21, 21, 21, 14, 14, 14, 14, 14, 8, 8, 8, 8, 8],
        
        'smallLocalLeague' => [],
        'largeLocalLeague' => []
        
    ];

    public static function getSmallSinglesLocalTestData() : array
    {
        return self::getTournamentTestData('smallSingleLocal');
    }

    public static function getLargeSinglesLocalTestData() : array
    {
        return self::getTournamentTestData('largeSingleLocal');
    }

    public static function getVeryLargeSinglesLocalTestData() : array
    {
        return self::getTournamentTestData('veryLargeSingleLocal');
    }

    public static function getSmallThreePlayerTeamLocalTestData() : array
    {
        return self::getTournamentTestData('smallThreePlayerTeamLocal', 3);
    }

    public static function getLargeThreePlayerTeamLocalTestData() : array
    {
        return self::getTournamentTestData('largeThreePlayerTeamLocal', 3);
    }

    public static function getLargeFivePlayerTeamLocalTestData() : array
    {
        return self::getTournamentTestData('largeFivePlayerTeamLocal', 5);
    }

    public static function getSmallSingleMasterTestData() : array
    {
        return self::getTournamentTestData('smallSingleMaster', 1);
    }

    public static function getLargeSingleMasterTestData() : array
    {
        return self::getTournamentTestData('largeSingleMaster', 1);
    }

    public static function getVeryLargeSingleMasterTestData() : array
    {
        return self::getTournamentTestData('veryLargeSingleMaster', 1);
    }

    private static function getTournamentTestData($type, $playersInTeam = 1)
    {
        return self::getTestData(
            self::getResultsData(self::$tournaments[$type]),
            $playersInTeam
        );
    }

    private static function getTestData(array $data, $playersInTeam) : array
    {
        return [
            'input' => self::getInputData($data, $playersInTeam),
            'expectedResult' => self::getExpectedResults($data, $playersInTeam)
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

    private static function getInputData($inputData, $playersInTeam) : TournamentResults
    {
        $results = [];
        $teamCounter = $playersInTeam;
        $place = 0;

        foreach ($inputData as $i => $data) {

            if ($playersInTeam == 1) {
                $place++;
            } else {
                if ($teamCounter >= $playersInTeam) {
                    $place++;
                    $teamCounter = 1;
                } else {
                    $teamCounter++;
                }
            }

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

    private static function getExpectedResults($inputData, $playersInTeam) : array
    {
        $results = [];
        $place = 0;
        $teamCounter = $playersInTeam;

        foreach ($inputData as $i => $expectedResult) {
            if ($playersInTeam == 1) {
                $place++;
            } else {
                if ($teamCounter >= $playersInTeam) {
                    $place++;
                    $teamCounter = 1;
                } else {
                    $teamCounter++;
                }
            }

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
