<?php
namespace App\Controller;

use App\DataFixtures\MongoDB\TournamentListFixtures;
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

        $this->assertCount(5, $tournaments);

        $expectedDate = new \DateTime();
        $expectedDate->sub(\DateInterval::createFromDateString('3 days'));
        $this->assertEquals($expectedDate->format('d.m.Y'), $tournaments[0]->date, 'Tournament date is not in expected format');
    }

    public function testListTournamentsShouldReturnFutureTournaments() {

    }

    public function testAddTournamentShouldCreateNewTournament() {

    }

    public function testGetTournamentShouldReturnTournamentWithResults() {

    }
}