<?php
namespace App\Controller;

class RankingController extends AppController
{

    public function list() {
        $players = $this->getMongo()
            ->getRepository('App:Ranking')
            ->getRanking();

        return $this->json($this->getSerializer()->normalize($players, 'json'));
    }
}