<?php
namespace App\DataFixtures\MongoDB\Development;

use App\Document\Player;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlayersFixtures extends Fixture
{
    public static $players = [];
    public const CLUBS = ['Ad Astra', 'Rogaty Szczur', 'Ordin'];
    public const TOWNS = ['Zielona Góra', 'Gorzów Wlkp.', 'Warszawa'];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $player = $this->createPlayer($i);
            $referenceId = 'player-'.$i;
            self::$players[] = $referenceId;
            $this->setReference($referenceId, $player);
            $manager->persist($player);
        }

        $manager->flush();
    }

    protected function createPlayer(int $randomPart): Player
    {
            $player = new Player();
            $player->setLegacyId(1000 + $randomPart);
            $player->setFirstName('Player' . $randomPart);
            $player->setName('Name' . $randomPart);
            $player->setCountry('PL');
            $player->setAssociation(self::CLUBS[$randomPart % 3]);
            $player->setNickname('alias' . $randomPart);
            $player->setTown(self::TOWNS[$randomPart % 3]);

            return $player;
    }


}
