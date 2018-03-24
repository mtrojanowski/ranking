<?php
namespace App\Controller;

use App\Controller\dto\TournamentResults;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Routing\Annotation\Route;

class ResultsController extends AppController
{
    /**
     * @Route("tournamentResults", methods="POST")
     */
    public function createTournamentResults(Request $request)
    {
        try {
            /** @var TournamentResults $tournamentResults */
            $tournamentResults = $this->getSerializer()->deserialize(
                $request->getContent(),
                TournamentResults::class,
                'json'
            );

            $results = $this->getSerializer()->denormalize(
                $tournamentResults->getResults(),
                'App\Controller\dto\Result[]'
            );

            $tournamentResults->setResults($results);
        } catch(NotEncodableValueException $e) {
            return $this->json($this->getError('Invalid data'), 400);
        }

    }

}