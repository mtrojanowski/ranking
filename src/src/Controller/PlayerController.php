<?php
namespace App\Controller;

use App\Document\Player;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class PlayerController extends AppController
{

    /**
     * @Route("/players", name="players_list", methods="GET")
     */
    public function listPlayers() {
        $players = $this->getMongo()
            ->getRepository('App:Player')
            ->findAll();

        return $this->json($this->getSerializer()->normalize($players, 'json'));
    }

    /**
     * @Route("/players", name="create_player", methods="POST")
     */
    public function createPlayer(Request $request) {

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

        $mongoManager = $this->getMongo()
            ->getManager();

        $mongoManager->persist($player);

        try {
            $mongoManager->flush();
        } catch (\MongoDuplicateKeyException $e) {
            return $this->json($this->getError('Player already exists'), 409);
        }

        return $this->json(['id' => $player->getLegacyId()], 201);
    }
}