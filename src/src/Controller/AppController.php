<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AppController extends AbstractController
{
    private ?Serializer $serializer = null;

    protected const CACHE_FOR_A_MINUTE = [
        'Cache-Control' => 'public, max-age=60'
    ];

    protected function getSerializer() : Serializer
    {
        if ($this->serializer == null) {
            $encoders = [ new JsonEncoder() ];
            $normalizers = [ new ObjectNormalizer(), new ArrayDenormalizer() ];

            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }

    protected function getError(string $message) : array
    {
        return [
            'message' => $message
        ];
    }
}
