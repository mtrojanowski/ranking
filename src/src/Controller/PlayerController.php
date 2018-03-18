<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AppController
{

    /**
     * @Route("/players", name="players_list")
     */
    public function listPlayers() {
        $players = $this->getMongo()
            ->getRepository('App:Player')
            ->findAll();

        return $this->json($players);
    }

}