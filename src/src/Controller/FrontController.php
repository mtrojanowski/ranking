<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends AbstractController
{
    public function front(): Response
    {
        $response =  $this->render('index.html.twig');
        $response->headers->add([
            'Content-Security-Policy' => "default-src 'self'; " .
                "script-src 'self' https://code.jquery.com https://cdnjs.cloudflare.com https://stackpath.bootstrapcdn.com; " .
                "style-src 'self' https://stackpath.bootstrapcdn.com; " .
                "connect-src 'self'; "
        ]);

        return $response;
    }
}
