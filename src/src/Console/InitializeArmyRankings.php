<?php
namespace App\Console;

use App\Document\Ranking;
use App\Document\Result;
use App\Document\Season;
use App\Service\RankingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeArmyRankings extends Command
{
    protected static $defaultName = 'ranking:army:initialize';
    private RankingService $rankingService;

    private DocumentManager $documentManager;

    public function __construct(RankingService $rankingService, DocumentManager $documentManager)
    {
        parent::__construct();

        $this->rankingService = $rankingService;
        $this->documentManager = $documentManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initializes army rankings')
            ->setHelp('Initializes army rankings for current season. Can be used when new armies are configured.')
            ->addArgument('seasonId', InputArgument::OPTIONAL, 'ID of the season to recalculate. The active season is recalculated by default.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Initialize army rankings',
            '===================',
            '',
        ]);

        $seasonId = $input->getArgument('seasonId');

        if (empty($seasonId)) {
            /** @var Season $season */
            $season = $this->documentManager->getRepository(Season::class)->getActiveSeason();
            $seasonId = $season->getId();
        } else {
            $season = $this->documentManager->getRepository(Season::class)->find($seasonId);
        }

        $rankingRepository = $this->documentManager->getRepository(Ranking::class);
        $rankings = $rankingRepository->findBy([
            'seasonId' => $seasonId]);
        $playersInRanking = [];

        foreach ($rankings as $playerInRanking) {
            /** @var Ranking $playerInRanking */
            $playersInRanking[] = $playerInRanking->getPlayerId();
        }

        $resultsRepository = $this->documentManager->getRepository(Result::class);

        $armies = [
            "UD",
            "WDG",
            "DE",
            "EOS",
            "KOE",
            "VC",
            "VS",
            "SE",
            "HE",
            "OG",
            "BH",
            "DH",
            "OK",
            "DL",
            "SA",
            "ID",
        ];

        foreach ($armies as $army) {
            $output->writeln("Initializing for ". $army);
            foreach ($playersInRanking as $playerId) {

                $resultsForArmy = $resultsRepository->findBy([
                    'playerId' => $playerId,
                    'seasonId' => $season->getId(),
                    'army' => $army
                ]);

                if (!isset($resultsForArmy[0])) {
                    continue;
                }

                $currentRanking = $this->rankingService->createInitialRanking(
                    $playerId,
                    $season->getId(),
                    $army
                );

                $this->documentManager->persist($this->rankingService->recalculateRanking($currentRanking, $season));
            }
        }

        $this->documentManager->flush();

        $output->writeln("Army rankings initialized.");

        return 0;
    }
}
