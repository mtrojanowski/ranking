<?php
namespace App\Controller;

use App\DataFixtures\MongoDB\AppFixtures;
use App\DataFixtures\MongoDB\OnePlayerFixtures;
use App\Document\Player;
use App\Repository\PlayerRepository;
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

    public function testCreatePlayer() {
        $client = static::createClient();

        $playerData = [
            'legacyId'    => 100,
            'name'        => 'Tester',
            'firstName'   => 'Testing',
            'association' => 'Club',
            'country'     => 'PL',
            'nickname'    => 'Nick',
            'town'        => 'City'
        ];

        $client->request('POST', '/api/players', [], [], [], json_encode($playerData));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $dm = $client->getContainer()->get('doctrine_mongodb');
        /** @var PlayerRepository $playersRepository */
        $playersRepository = $dm->getRepository(Player::class);

        $players = $playersRepository->findBy(['legacyId' => 100]);

        $this->assertCount(1, $players);
        $player = $players[0];
        $this->assertPlayersAreEqual($player, $playerData);
    }

    public function testCreatePlayerWithSameIdShouldThrowException() {
        $client = static::createClient();

        $this->loadFixtures([
            OnePlayerFixtures::class
        ], false, null, 'doctrine_mongodb');

        $playerData = [
            'legacyId'    => 1000,
            'name'        => 'Tester',
            'firstName'   => 'Testing',
            'association' => 'Club',
            'country'     => 'PL',
            'nickname'    => 'Nick',
            'town'        => 'City'
        ];

        $client->request('POST', '/api/players', [], [], [], json_encode($playerData));
        $this->assertEquals(409, $client->getResponse()->getStatusCode());
    }

    public function testCreatePlayerShouldSetNextLegacyIdAutomatically() {
        $client = static::createClient();
        $this->loadFixtures([
            OnePlayerFixtures::class
        ], false, null, 'doctrine_mongodb');

        $playerData = [
            'name'        => 'Tester',
            'firstName'   => 'Testing',
            'association' => 'Club',
            'country'     => 'PL',
            'nickname'    => 'Nick',
            'town'        => 'City'
        ];

        $client->request('POST', '/api/players', [], [], [], json_encode($playerData));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $responseJson = json_decode($client->getResponse()->getContent());
        $this->assertEquals(1001, $responseJson->id);
    }

    private function assertPlayersAreEqual(Player $player, array $playerArray) {
        $this->assertEquals($playerArray['legacyId'], $player->getLegacyId(), 'LegacyId does not equal');
        $this->assertEquals($playerArray['name'], $player->getName(), 'Name does not equal');
        $this->assertEquals($playerArray['firstName'], $player->getFirstName(), 'First name does not equal');
        $this->assertEquals($playerArray['association'], $player->getAssociation(), 'Association does not equal');
        $this->assertEquals($playerArray['country'], $player->getCountry(), 'Country does not equal');
        $this->assertEquals($playerArray['nickname'], $player->getNickname(), 'Nickname does not equal');
        $this->assertEquals($playerArray['town'], $player->getTown(), 'Town does not equal');
    }
}
