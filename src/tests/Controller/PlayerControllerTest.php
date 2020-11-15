<?php
namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PlayerControllerTest extends TestCase
{
    public function testGetPlayersList() {
        $client = new Client([
            'base_uri' => 'http://localhost:8000/api',
            'http_errors' =>  false
        ]);

        try {
            $response = $client->get("/players");
            $this->assertEquals(200, $response->getStatusCode());
        } catch (GuzzleException $e) {
            $this->fail($e->getMessage());
        }
    }
}
