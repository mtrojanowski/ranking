<?php
namespace App\Controller;

use App\Controller\dto\PlayerDto;
use App\Document\Player;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class PlayerController extends AppController
{

    public function listPlayers(DocumentManager $dm) {
        $players = $dm
            ->getRepository('App:Player')
            ->findAll();

        $playerDtos = [];
        foreach ($players as $player) {
            $playerDtos[] = $this->toPlayerDto($player);
        }

        return $this->json($this->getSerializer()->normalize($playerDtos, 'json'));
    }

    public function createPlayer(Request $request, DocumentManager $mongoManager) {

        try {
            /** @var Player $player */
            $player = $this->getSerializer()->deserialize(
                $request->getContent(),
                Player::class,
                'json'
            );
        } catch (NotEncodableValueException $e) {
            return $this->json($this->getError('Incorrect input data'), 400);
        }

        $mongoManager->persist($player);

        try {
            $mongoManager->flush();
        } catch (\MongoDuplicateKeyException $e) {
            return $this->json($this->getError('Player already exists'), 409);
        } catch (MongoDBException $e) {
            return $this->json($this->getError('Database exception'), 500);
        }

        return $this->json(['id' => $player->getLegacyId()], 201);
    }

    private function toPlayerDto(Player $player) : PlayerDto
    {
        return new PlayerDto(
            $player->getLegacyId(),
            $player->getFirstName(),
            $player->getNickname(),
            $player->getTown(),
            $player->getCountry(),
            $player->getAssociation());
    }
}