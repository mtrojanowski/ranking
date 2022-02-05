<?php
namespace App\Console;

use App\Document\Ranking;
use App\Document\Season;
use App\Service\RankingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDevelopmentData extends Command
{
    protected static $defaultName = 'ranking:loadDevelopmentData';

    private DocumentManager $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct();

        $this->documentManager = $documentManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Load development data to the database.')
            ->setHelp(
<<<EOF
        The command does the following by running other commands:
        
        1. Clear the database and load fixtures from the Development package (using the fixtures:load command)
        2. Initialize army rankings for all seasons
        3. Recalculate rankings for all seasons
EOF
);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Loading fixtures");
        $command = $this->getApplication()->find('doctrine:mongodb:fixtures:load');


        $fixturesInput = new ArrayInput([]);
        $fixturesInput->setInteractive(false);
        $command->run($fixturesInput, $output);

        // Get seasons from DB
        $seasons = $this->documentManager->getRepository(Season::class)->findAll();

        $initializeArmyRankingCommand = $this->getApplication()->find('ranking:army:initialize');
        $recalculateRankingCommand = $this->getApplication()->find('ranking:recalculate');

        foreach ($seasons as $season) {
            /** @var Season $season */
            $output->writeln('Initializing army rankings for season '.$season->getName());
            $initializeArmyRankingArguments = ['seasonId' => $season->getId()];
            $commandInput = new ArrayInput($initializeArmyRankingArguments);
            $initializeArmyRankingCommand->run($commandInput, $output);

            $output->writeln('Recalculating ranking for season '.$season->getName());
            $recalculateArguments = ['seasonId' => $season->getId()];
            $recalculateCommandInput = new ArrayInput($recalculateArguments);
            $recalculateRankingCommand->run($recalculateCommandInput, $output);
        }

        $output->writeln('Finished loading development data.');

        return 0;
    }
}
