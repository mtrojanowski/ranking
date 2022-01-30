<?php
namespace App\Console;


use App\Document\Ranking;
use App\Document\Season;
use App\Service\RankingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecalculateRanking extends Command
{
    protected static $defaultName = 'ranking:recalculate';
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
            ->setDescription('Recalculates ranking')
            ->setHelp('Recalculates the whole ranking for a given season. Useful when configuration changes.')
            ->addArgument('seasonId', InputArgument::OPTIONAL, 'ID of the season to recalculate. The active season is recalculated by default.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Recalculate ranking',
            '===================',
            '',
        ]);

        $seasonId = $input->getArgument('seasonId');

        if (empty($seasonId)) {
            /** @var Season $season */
            $season = $this->documentManager->getRepository('App:Season')->getActiveSeason();
            $seasonId = $season->getId();
        } else {
            $season = $this->documentManager->getRepository('App:Season')->find($seasonId);
        }

        $output->writeln("Recalculating ranking for season: {$season->getName()}(ID: {$seasonId}) ");

        $rankingRepository = $this->documentManager->getRepository('App:Ranking');
        $rankings = $rankingRepository->findBy(['seasonId' => $seasonId]);

        $toRecalculate = count($rankings);

        $steps = $toRecalculate > 30 ? 30 : $toRecalculate;
        $steps = $steps == 0 ? 1 : $steps;

        $progressBar = new ProgressBar($output, $steps);
        $progressBar->start();

        $i = 0;
        $step = (int) ($toRecalculate / $steps);

        foreach ($rankings as $currentRanking) {
            /** @var Ranking $currentRanking */

            $recalculatedRanking = $this->rankingService->recalculateRanking($currentRanking, $season);

            if ($recalculatedRanking->getTournamentCount() > 0) {
                $this->documentManager->persist($recalculatedRanking);
            } else {
                $this->documentManager->remove($recalculatedRanking);
            }

            if ($i % $step == 0) {
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $output->writeln("");

        $this->documentManager->flush();

        $output->writeln("Ranking recalculated");

        return 0;
    }
}
