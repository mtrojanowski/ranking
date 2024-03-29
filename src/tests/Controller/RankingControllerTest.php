<?php

namespace App\Controller;

use App\DataFixtures\MongoDB\ArchiveSeasonsFixtures;
use App\DataFixtures\MongoDB\RankingFixtures;
use App\Document\Season;
use App\Document\Tournament;
use App\Repository\SeasonRepository;
use App\Repository\TournamentRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RankingControllerTest extends WebTestCase
{
    public function testShouldReturnRankingForActiveSeasonByDefault()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            RankingFixtures::class
        ], false);

        $client->request('GET', '/api/ranking', [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $dm = $client->getContainer()->get('doctrine_mongodb');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $season = $seasonRepository->getActiveSeason();

        $results = json_decode($client->getResponse()->getContent());

        $this->assertCount(10, $results->ranking);

        $this->assertTournamentInSeason($results, $season, $dm);
    }

    // Return ranking for given season
    public function testShouldReturnRankingForAGivenSeason()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            RankingFixtures::class
        ], false);

        $dm = $client->getContainer()->get('doctrine_mongodb');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $inactiveSeason = $seasonRepository->findOneBy(['active' => 0]);
        $inactiveSeasonId = $inactiveSeason->getId();

        $client->request('GET', "/api/ranking/$inactiveSeasonId", [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $results = json_decode($client->getResponse()->getContent());

        $this->assertCount(7, $results->ranking);

        $this->assertTournamentInSeason($results, $inactiveSeason, $dm);
    }

    public function testShouldReturnArmyRankingForActiveSeasonByDefault()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            RankingFixtures::class
        ], false);

        $client->request('GET', '/api/ranking', ["army" => "VC"], [], ["HTTP_CONTENT_TYPE" => "application/json"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $dm = $client->getContainer()->get('doctrine_mongodb');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $season = $seasonRepository->getActiveSeason();

        $results = json_decode($client->getResponse()->getContent());

        $this->assertCount(3, $results->ranking);

        $this->assertTournamentInSeason($results, $season, $dm);
    }

    public function testShouldReturnArmyRankingForAGivenSeason()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            RankingFixtures::class
        ], false);

        $dm = $client->getContainer()->get('doctrine_mongodb');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $inactiveSeason = $seasonRepository->findOneBy(['active' => 0]);
        $inactiveSeasonId = $inactiveSeason->getId();

        $client->request('GET', "/api/ranking/$inactiveSeasonId", ['army' => 'VC'], [], ["HTTP_CONTENT_TYPE" => "application/json"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $results = json_decode($client->getResponse()->getContent());

        $this->assertCount(2, $results->ranking);

        $this->assertTournamentInSeason($results, $inactiveSeason, $dm);
    }

    public function testShouldReturnIndividualRankingForActiveSeasonByDefault()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            RankingFixtures::class
        ], false);

        $client->request('GET', '/api/ranking-individual/1001', [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $results = json_decode($client->getResponse()->getContent());

        $this->assertCount(4, $results->tournaments);
        $this->assertEquals("1001", $results->player->legacyId);

        $seasonTournamentIds = [1120, 1121, 1122, 1123];

        foreach ($results->tournaments as $tournament) {
            $this->assertContains($tournament->legacyId, $seasonTournamentIds);
        }
    }

    public function testShouldReturnIndividualRankingForAGivenSeason()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            RankingFixtures::class
        ], false);

        $dm = $client->getContainer()->get('doctrine_mongodb');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $dm->getRepository(Season::class);
        $inactiveSeason = $seasonRepository->findOneBy(['active' => 0]);
        $inactiveSeasonId = $inactiveSeason->getId();

        $client->request('GET', '/api/ranking-individual/1001', ["seasonId" => $inactiveSeasonId], [], ["HTTP_CONTENT_TYPE" => "application/json"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');

        $results = json_decode($client->getResponse()->getContent());

        $this->assertCount(4, $results->tournaments);
        $this->assertEquals("1001", $results->player->legacyId);

        $seasonTournamentIds = [1220, 1221, 1222, 1223];

        foreach ($results->tournaments as $tournament) {
            $this->assertContains($tournament->legacyId, $seasonTournamentIds);
        }
    }

    public function testListOfArchiveSeasonsShouldReturnList()
    {
        $client = static::createClient();

        $this->getDocumentManager()->loadFixtures([
            ArchiveSeasonsFixtures::class
        ], false);

        $client->request('GET', '/api/archive-seasons', [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Response status is not 200');


        $listOfArchiveSeasons = json_decode($client->getResponse()->getContent());

        $this->assertCount(2, $listOfArchiveSeasons->seasons);

        $archiveSeasonNames = ['2018', '2017'];

        foreach ($listOfArchiveSeasons->seasons as $season) {
            $this->assertContains($season->name, $archiveSeasonNames);
        }
    }

    private function assertTournamentInSeason($results, $season, $dm)
    {
        $tournamentId = $results->ranking[0]->tournamentsIncluded[0];
        /** @var TournamentRepository $tournamentsRepository **/
        $tournamentsRepository = $dm->getRepository(Tournament::class);
        /** @var Tournament $tournament */
        $tournament = $tournamentsRepository->findOneBy(["legacyId" => $tournamentId]);
        $this->assertEquals($season->getId(), $tournament->getSeason());
    }

    private function getDocumentManager(): AbstractDatabaseTool {
        return static::getContainer()->get(DatabaseToolCollection::class)->get(null, 'doctrine_mongodb');
    }
}
