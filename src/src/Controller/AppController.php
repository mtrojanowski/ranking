<?php
namespace App\Controller;


use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    protected function getMongo():ManagerRegistry {
        return $this->get('doctrine_mongodb');
    }

}