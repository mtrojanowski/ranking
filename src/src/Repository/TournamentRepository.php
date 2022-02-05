<?php
namespace App\Repository;

use App\Document\Tournament;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\InvalidArgumentException;

class TournamentRepository extends DocumentRepository
{
    public function getTournaments(string $previous, string $activeSeasonId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $todayDate = new \DateTime();
        $todayDate->setTime(0, 0, 0);

        $queryBuilder
            ->field("season")->equals($activeSeasonId);

        if ($previous == 'true') {
            $queryBuilder
                ->field('date')->lt($todayDate);
        } else {
            $queryBuilder
                ->field('date')->gte($todayDate);
        }

        $queryBuilder->sort("date", 1);

        return $queryBuilder->getQuery()->execute()->toArray();
    }

    public function getLastLegacyId(): int
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->sort('legacyId', -1)
            ->limit(1);

        $tournament = $queryBuilder->getQuery()->getSingleResult();

        return !empty($tournament) ? $tournament->getLegacyId() : 0;
    }

    public function findTournaments(array $tournamentIds): array
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->field('legacyId')->in($tournamentIds);
        $queryBuilder->sort('date', -1);

        return $queryBuilder->getQuery()->execute()->toArray();
    }

    public function getById($id): ?Tournament {
        // First try with id as ObjectId
        /** @var Tournament $tournament */
        $tournament = null;
        try {
            $tournamentId = new ObjectId($id);
            $tournament = $this->find($tournamentId);
        } catch (InvalidArgumentException $exception) {
            // Most probably legacy ID was used
        }
        // Then try to use id as legacyId

        if ($tournament == null) {
            $tournament = $this->findOneBy(['legacyId' => (int) $id]);
        }

        return $tournament;
    }
}
