<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Player;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class OnePlayerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $player = new Player();
        $player->setLegacyId(1000);
        $player->setFirstName('Player');
        $player->setName('Name');
        $player->setCountry('PL');
        $player->setAssociation('Club');
        $player->setNickname('alias');
        $player->setTown('Town');
        $manager->persist($player);

        $manager->flush();
    }
}
