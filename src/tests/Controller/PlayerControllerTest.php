<?php
namespace App\Controller;

use App\DataFixtures\MongoDB\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testGetPlayersList() {
        $client = static::createClient();

        $this->loadFixtures([
            AppFixtures::class
        ], false, null, 'doctrine_mongodb');

        $client->request('GET', '/api/players');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $playersArray = json_decode($client->getResponse()->getContent());
        $this->assertCount(10, $playersArray, 'Should return 10 players.');
    }
}
