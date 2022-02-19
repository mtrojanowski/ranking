<?php
namespace App\DataFixtures\MongoDB\Development;

use App\Controller\dto\Result;
use App\Controller\dto\TournamentResults;
use App\Document\Player;
use App\Document\Season;
use App\Document\Tournament;
use App\Service\ResultsService;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TournamentsFixtures extends Fixture implements DependentFixtureInterface
{
    private static array $armies = ['VC', 'SE', 'EoS', 'DH'];

    private ResultsService $resultsService;

    public function __construct(ResultsService $resultsService)
    {
        $this->resultsService = $resultsService;
    }

    public function load(ObjectManager $manager)
    {
        // Previous season
        /** @var Season $season */
        $season = $this->getReference(SeasonFixtures::PREVIOUS_SEASON);
        $previousSeasonDate = new \DateTime("first day of February previous year");
        for ($i = 0; $i < 3; $i++) {
            $this->persistTournamentWithResults($manager, $season, $previousSeasonDate->add(new \DateInterval("P{$i}M")), $i);
        }

        // Active Season
        /** @var Season $activeSeason */
        $activeSeason = $this->getReference(SeasonFixtures::CURRENT_SEASON);
        $today = new \DateTime();

        for ($j = $i; $j < $i + 3; $j++) {
            $this->persistTournamentWithResults($manager, $activeSeason, $today->sub(new \DateInterval("P{$j}D")), $j);
        }

        // Future tournament
        $futureTournament = $this->getTournament($j, new \DateTime("+1 day"), $activeSeason);
        $manager->persist($futureTournament);

        $manager->flush();
    }

    private function persistTournamentWithResults(ObjectManager $manager, Season $season, \DateTime $tournamentDate, int $index) {
        $tournament = $this->getTournament($index, $tournamentDate, $season);
        $tournament->setStatus("OK");
        $manager->persist($tournament);
        $results = $this->getResults($index);
        $resultsDto = new TournamentResults();
        $resultsDto->setTournamentId($tournament->getLegacyId());
        $resultsDto->setResults($results);
        $tournamentResults = $this->resultsService->createTournamentResults($tournament, $resultsDto);
        foreach ($tournamentResults as $result) {
            $manager->persist($result);
        }
    }

    protected function getTournament(int $i, \DateTime $date, Season $season): Tournament
    {
        $tournament = new Tournament();
        $tournament->setLegacyId(1000 + $i);
        $tournament->setName("Tournament$i");
        $tournament->setTown('ATown');
        $tournament->setDate($date);
        $tournament->setOrganiser("Org");
        $tournament->setPlayersInTeam(1);
        $tournament->setPoints(4500);
        $tournament->setRank('local');
        $tournament->setSeason($season->getId());
        $tournament->setType('single');
        $tournament->setStatus('NEW');
        $tournament->setVenue("A venue");

        return $tournament;
    }

    protected function getResults(int $startWithPlayer): array {
        $results = [];
        $players = PlayersFixtures::$players;
        $playersCount = count($players);

        if ($startWithPlayer) {
            $players = array_merge(array_slice($players, $startWithPlayer), array_slice($players, 0, $startWithPlayer));
        }

        for ($i = 0; $i < $playersCount; $i++) {
            /** @var Player $player */
            $player = $this->getReference($players[$i]);

            $result = new Result();
            $result->setPlayerId($player->getLegacyId());
            $result->setPlace($i + 1);
            $result->setArmy(self::$armies[$i % 4]);
            $result->setJudge(0);

            $results[] = $result;
        }

        return $results;
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
            PlayersFixtures::class
        ];
    }


}
