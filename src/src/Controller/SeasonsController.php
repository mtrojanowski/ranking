<?php
namespace App\Controller;

use App\Controller\dto\SeasonDto;
use App\Document\Season;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class SeasonsController extends AppController
{
    private const CACHE_FOR_AN_HOUR = [
        'Cache-Control' => 'public, max-age=3600'
    ];

    public function listArchive(DocumentManager $dm): JsonResponse {
        $seasons = $dm
            ->getRepository('App:Season')
            ->findBy(['active' => false], ['endDate' => -1]);

        $seasonDtos = [];
        foreach ($seasons as $season) {
            /** @var Season $season */
            $seasonDtos[] = $this->toSeasonsDto($season);
        }

        $headers = self::CACHE_FOR_AN_HOUR;
        /** @var Season $latestSeason */
        $latestSeason = $seasons[0];
        $headers["Last-Modified"] = $latestSeason->getEndDate()->format("D, d M Y H:i:s"). " GMT";

        return $this->json($this->getSerializer()->normalize(["seasons" => $seasonDtos], 'json'), 200, $headers);
    }

    private function toSeasonsDto(Season $season) : SeasonDto
    {
        return new SeasonDto(
            $season->getId(),
            $season->getName()
            );
    }
}
