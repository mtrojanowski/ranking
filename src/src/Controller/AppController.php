<?php
namespace App\Controller;


use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AppController extends Controller
{
    private $serializer;

    protected function getSerializer() : Serializer
    {
        if ($this->serializer == null) {
            $encoders = [ new JsonEncoder() ];
            $normalizers = [ new ObjectNormalizer() ];

            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }

    protected function getMongo() : ManagerRegistry
    {
        return $this->get('doctrine_mongodb');
    }

    protected function getError(string $message) : array
    {
        return [
            'message' => $message
        ];
    }
}