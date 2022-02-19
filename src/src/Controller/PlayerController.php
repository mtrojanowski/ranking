<?php
namespace App\Controller;

use App\Controller\dto\PlayerDto;
use App\Document\Player;
use App\Repository\PlayerRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\Exception as MongoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class PlayerController extends AppController
{

    public function listPlayers(DocumentManager $dm): JsonResponse {
        $players = $dm
            ->getRepository(Player::class)
            ->findAll();

        $playerDtos = [];
        foreach ($players as $player) {
            $playerDtos[] = $this->toPlayerDto($player);
        }

        return $this->json($this->getSerializer()->normalize($playerDtos, 'json'), 200, self::CACHE_FOR_A_MINUTE);
    }

    public function createPlayer(Request $request, DocumentManager $mongoManager): JsonResponse {

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

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $mongoManager->getRepository(Player::class);

        if ($player->getLegacyId() == null) {
            $player->setLegacyId((string)($playerRepository->getHighestLegacyId() + 1));
        }

        $mongoManager->persist($player);

        try {
            $mongoManager->flush();
        } catch (BulkWriteException $e) {
            if ($e->getCode() == 11000) {
                return $this->json($this->getError('Player with id ' . $player->getLegacyId() . 'already exists '), 409);
            } else {
                return $this->json($this->getError('Database exception' . get_class($e)), 500);
            }
        } catch (MongoException | MongoDBException $e) {
            return $this->json($this->getError('Database exception' . get_class($e)), 500);
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
