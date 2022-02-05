<?php
namespace App\Controller;

use App\DataFixtures\MongoDB\TournamentListFixtures;
use App\Document\Tournament;
use App\Repository\TournamentRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TournamentControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testListTournamentsShouldRequirePreviousParameter() {
        $client = static::createClient();

        $client->request('GET', '/api/tournaments');

        $this->assertEquals(400, $client->getResponse()->getStatusCode(), "GET tournaments didn't return 400 when no previous parameter sent.");
    }

    public function testListTournamentsShouldReturnPreviousTournaments() {
        $client = static::createClient();

        $this->loadFixtures([
            TournamentListFixtures::class
        ], false, null, 'doctrine_mongodb');

        $client->request('GET', '/api/tournaments?previous=true');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $tournaments = json_decode($client->getResponse()->getContent());

        $this->assertCount(6, $tournaments);

        $expectedDate = new \DateTime();
        $expectedDate->sub(\DateInterval::createFromDateString('3 days'));
        $this->assertEquals($expectedDate->format('d.m.Y'), $tournaments[0]->date, 'Tournament date is not in expected format');
    }

    public function testListTournamentsShouldReturnFutureTournaments() {
        $client = static::createClient();

        $this->loadFixtures([
            TournamentListFixtures::class
        ], false, null, 'doctrine_mongodb');

        $client->request('GET', '/api/tournaments?previous=false');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $tournaments = json_decode($client->getResponse()->getContent());

        $this->assertCount(5, $tournaments);
    }

    public function testAddTournamentShouldCreateNewTournament() {
        $client = static::createClient();

        $body = <<<EOL
        {
            "name": "Test new tournament",
            "date": "2020-01-12",
            "points": 4500,
            "town": "Poznań",
            "type": "single",
            "playersInTeam": 1,
            "rank": "local"
        }
EOL;
        $client->request('POST', '/api/tournaments', [], [], ["HTTP_CONTENT_TYPE" => "application/json"], $body);

        $this->assertEquals(201, $client->getResponse()->getStatusCode(), 'Response status is not 201');

        $tournamentResponse = json_decode($client->getResponse()->getContent());
        $tournamentId = $tournamentResponse->id;

        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var TournamentRepository $tournamentsRepository */
        $tournamentsRepository = $dm->getRepository(Tournament::class);

        /** @var Tournament $newTournament */
        $newTournament = $tournamentsRepository->findOneBy(['legacyId' => $tournamentId]);

        $this->assertEquals("Test new tournament", $newTournament->getName());
        $this->assertEquals("single", $newTournament->getType());
        $this->assertEquals("local", $newTournament->getRank());
    }

    public function testGetTournamentShouldReturnTournamentWithResults() {
        $client = static::createClient();

        $this->loadFixtures([
            TournamentListFixtures::class
        ], false, null, 'doctrine_mongodb');

        $client->request('GET', '/api/tournaments/5fca99fd752742d853ccfd23');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $tournamentData = json_decode($client->getResponse()->getContent());

        $this->assertEquals(1123, $tournamentData->legacyId);
        $this->assertCount(10, $tournamentData->results);
        $this->assertEquals(1000, $tournamentData->results[0]->playerId);
    }
}
