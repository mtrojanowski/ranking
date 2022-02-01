<?php
namespace App\Controller;

use App\Controller\dto\SeasonDto;
use App\Document\Season;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeasonsController extends AppController
{

    public function listArchive(DocumentManager $dm): JsonResponse {
        $seasons = $dm
            ->getRepository('App:Season')
            ->findBy(['active' => false], ['endDate' => -1]);

        $seasonDtos = [];
        foreach ($seasons as $season) {
            /** @var Season $season */
            $seasonDtos[] = $this->toSeasonsDto($season);
        }

        return $this->json($this->getSerializer()->normalize(["seasons" => $seasonDtos], 'json'));
    }

    private function toSeasonsDto(Season $season) : SeasonDto
    {
        return new SeasonDto(
            $season->getId(),
            $season->getName()
            );
    }
}
