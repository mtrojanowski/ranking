<?php


namespace App\Controller;


use App\DataFixtures\MongoDB\TournamentResultsFixtures;
use App\Document\Ranking;
use App\Document\Result;
use App\Document\Season;
use App\Document\Tournament;
use App\Repository\RankingRepository;
use App\Repository\ResultsRepository;
use App\Repository\SeasonRepository;
use App\Repository\TournamentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use MongoDB\BSON\ObjectId;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResultsControllerTest extends WebTestCase
{

    // should create results for a tournament and change status to OK and lastModified on ranking to current datetime, code 201
    public function testShouldCreateResults()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_ID;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" },
            {"playerId": "1003", "army": "VC", "place": "3", "judge": "0" },
            {"playerId": "1004", "army": "OK", "place": "4", "judge": "0" },
            {"playerId": "1005", "army": "VS", "place": "5", "judge": "0" },
            {"playerId": "1006", "army": "WoDG", "place": "6", "judge": "0" },
            {"playerId": "1007", "army": "BH", "place": "7", "judge": "0" },
            {"playerId": "1008", "army": "UD", "place": "8", "judge": "0" },
            {"playerId": "1009", "army": "VS", "place": "9", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response status is not 201');

        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var TournamentRepository $tournamentsRepository */
        $tournamentsRepository = $dm->getRepository(Tournament::class);

        /** @var Tournament $updatedTournament */
        $updatedTournament = $tournamentsRepository->find(new ObjectId($tournamentId));

        $this->assertEquals("OK", $updatedTournament->getStatus());

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);

        $season = $seasonRepository->getActiveSeason();

        // Verify Last updated is within last 5 seconds
        $now = time();
        $this->assertLessThanOrEqual($now, $season->getRankingLastModified()->getTimestamp());
        $this->assertGreaterThanOrEqual($now - 5, $season->getRankingLastModified()->getTimestamp());
    }

    // should create results for a tournament when legacyId used
    public function testCreateResultsWithLegacyId()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_LEGACY_ID;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response status is not 201');

        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var TournamentRepository $tournamentsRepository */
        $tournamentsRepository = $dm->getRepository(Tournament::class);

        /** @var Tournament $updatedTournament */
        $updatedTournament = $tournamentsRepository->find(new ObjectId(TournamentResultsFixtures::TOURNAMENT_ID));

        $this->assertEquals("OK", $updatedTournament->getStatus());
    }

    // should return 400 when invalid tournament sent
    public function testShouldReturn400WhenInvalidTournamentSent()
    {
        $client = static::createClient();

        $tournamentId = 1234;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(400, $client->getResponse()->getStatusCode(), 'Response status is not 400');
    }

    // should return 400 when invalid tournament type used
    public function testShouldReturn400WhenTournamentHasInvalidType()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::INVALID_TYPE_TOURNAMENT_ID;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(400, $client->getResponse()->getStatusCode(), 'Response status is not 400');
    }

    // should return 422 when wrong players sent
    public function testShouldReturn422WhenWrongPlayersSent()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_ID;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1020", "army": "DL", "place": "2", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(422, $client->getResponse()->getStatusCode(), 'Response status is not 422');
    }

    // should overwrite tournament results
    public function testShouldOverwriteTournamentResults()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_LEGACY_ID;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" },
            {"playerId": "1003", "army": "VC", "place": "3", "judge": "0" },
            {"playerId": "1004", "army": "OK", "place": "4", "judge": "0" },
            {"playerId": "1005", "army": "VS", "place": "5", "judge": "0" },
            {"playerId": "1006", "army": "WoDG", "place": "6", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response status is not 201');

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1007", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1008", "army": "DL", "place": "2", "judge": "0" },
            {"playerId": "1009", "army": "VC", "place": "3", "judge": "0" }
            
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response status is not 201');


        $dm = $client->getContainer()->get('doctrine_mongodb');

        /** @var ResultsRepository $resultsRepository */
        $resultsRepository = $dm->getRepository(Result::class);

        $tournamentResults = $resultsRepository->getTournamentResults($tournamentId);

        $this->assertCount(3, $tournamentResults);

        /** @var Result $firstResult */
        $firstResult = $tournamentResults[0];
        $this->assertEquals("1007", $firstResult->getPlayerId());
    }

    // should recalculate ranking for players from the tournament, as well as army rankings, creating new rankings if needed
    public function testShouldRecalculateRankingsOnResultsSubmission()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $this->sendFirstTournamentResults($client, TournamentResultsFixtures::DIFFERENT_TOURNAMENT_ID);

        /** @var ManagerRegistry $dm **/
        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $dm->getRepository(Ranking::class);

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);

        $season = $seasonRepository->getActiveSeason();

        $rankingAfterFirstTournament = $rankingRepository->getRanking($season->getId());
        $VCRankingAfterFirstTournament = $rankingRepository->getRanking($season->getId(), "VC");

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_ID;

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1009", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" },
            {"playerId": "1003", "army": "VC", "place": "3", "judge": "0" },
            {"playerId": "1004", "army": "OK", "place": "4", "judge": "0" },
            {"playerId": "1005", "army": "VC", "place": "5", "judge": "0" },
            {"playerId": "1006", "army": "WoDG", "place": "6", "judge": "0" },
            {"playerId": "1007", "army": "BH", "place": "7", "judge": "0" },
            {"playerId": "1008", "army": "UD", "place": "8", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $currentRanking = $rankingRepository->getRanking($season->getId());
        $currentVCRanking = $rankingRepository->getRanking($season->getId(), "VC");

        $this->assertNotSameSize($rankingAfterFirstTournament, $currentRanking, "Rankings should have different entries");
        $this->assertNotSameSize($VCRankingAfterFirstTournament, $currentVCRanking, "Army rankings should have different amount of entries");

        // First player should change in ranking and should have two tournaments counted
        /** @var Ranking $previousFirstPlayer */
        $previousFirstPlayer = $rankingAfterFirstTournament[0];
        /** @var Ranking $currentFirstPlayer */
        $currentFirstPlayer = $currentRanking[0];
        $this->assertNotSame($previousFirstPlayer->getPlayerId(), $currentFirstPlayer->getPlayerId());
        $this->assertEquals(2, $currentFirstPlayer->getTournamentsAttendedCount());
        $this->assertEquals(2, $currentFirstPlayer->getTournamentsAttendedCount());
        $this->assertCount(2, $currentFirstPlayer->getTournamentsIncluded());

        // First player for VC should not change and have two tournaments counted
        /** @var Ranking $previousFirstVCPlayer */
        $previousFirstVCPlayer = $VCRankingAfterFirstTournament[0];
        /** @var Ranking $currentFirstVCPlayer */
        $currentFirstVCPlayer = $currentVCRanking[0];
        $this->assertSame($previousFirstVCPlayer->getPlayerId(), $currentFirstVCPlayer->getPlayerId());
        $this->assertEquals(2, $currentFirstVCPlayer->getTournamentsAttendedCount());
        $this->assertEquals(2, $currentFirstVCPlayer->getTournamentsAttendedCount());
        $this->assertCount(2, $currentFirstVCPlayer->getTournamentsIncluded());
    }

    // overwriting a tournament result, and removing a player from the result should recalculate their ranking and remove ranking if that was their last ranking
    public function testOverwritingResultsWithRemovingPlayer()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_ID;
        $this->sendFirstTournamentResults($client, $tournamentId);

        /** @var ManagerRegistry $dm **/
        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $dm->getRepository(Ranking::class);

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $season = $seasonRepository->getActiveSeason();

        $rankingAfterFirstTournament = $rankingRepository->getRanking($season->getId());

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1002", "army": "DL", "place": "1", "judge": "0" },
            {"playerId": "1001", "army": "WoDG", "place": "2", "judge": "0" },
            {"playerId": "1004", "army": "VC", "place": "3", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $currentRanking = $rankingRepository->getRanking($season->getId());

        // First player should change in ranking
        /** @var Ranking $previousFirstPlayer */
        $previousFirstPlayer = $rankingAfterFirstTournament[0];
        /** @var Ranking $currentFirstPlayer */
        $currentFirstPlayer = $currentRanking[0];

        $this->assertNotSame($previousFirstPlayer->getPlayerId(), $currentFirstPlayer->getPlayerId());

        // Player removed from tournament should have the Ranking removed as she has no more tournaments in season
        $this->assertCount(3, $currentRanking);
        foreach($currentRanking as $rankingEntry) {
            /** @var Ranking $rankingEntry */
            $this->assertNotEquals("1003", $rankingEntry->getPlayerId());
        }
    }

    // overwriting a tournament result and changing the army recalculates both army rankings
    public function testOverwritingResultsWithChangingArmy()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            TournamentResultsFixtures::class
        ], false);

        $tournamentId = TournamentResultsFixtures::TOURNAMENT_ID;
        $this->sendFirstTournamentResults($client, $tournamentId);

        /** @var ManagerRegistry $dm **/
        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var RankingRepository $rankingRepository */
        $rankingRepository = $dm->getRepository(Ranking::class);

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $season = $seasonRepository->getActiveSeason();
        $activeSeasonId = $season->getId();

        $previousDLRanking = $rankingRepository->getRanking($activeSeasonId, "DL");
        $previousVCRanking = $rankingRepository->getRanking($activeSeasonId, "VC");

        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "VC", "place": "2", "judge": "0" },
            {"playerId": "1003", "army": "VC", "place": "3", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        // DL ranking should be empty now
        $currentDLRanking = $rankingRepository->getRanking($activeSeasonId, "DL");

        $this->assertNotSameSize($currentDLRanking, $previousDLRanking);
        $this->assertCount(0, $currentDLRanking);

        // VC ranking should have two players now
        $currentVCRanking = $rankingRepository->getRanking($activeSeasonId, "VC");
        $this->assertNotSameSize($currentVCRanking, $previousVCRanking);
        $this->assertCount(2, $currentVCRanking);
    }

    private function sendFirstTournamentResults(KernelBrowser $client, $tournamentId)
    {
        $body = <<<EOL
        {
          "tournamentId": "$tournamentId",
          "results": [
            {"playerId": "1001", "army": "WoDG", "place": "1", "judge": "0" },
            {"playerId": "1002", "army": "DL", "place": "2", "judge": "0" },
            {"playerId": "1003", "army": "VC", "place": "3", "judge": "0" }
        ]}
EOL;
        $client->request('POST', '/api/tournament-results', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

    }

    private function getDocumentManager(): AbstractDatabaseTool {
        return static::getContainer()->get(DatabaseToolCollection::class)->get(null, 'doctrine_mongodb');
    }
}
