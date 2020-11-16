<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testGetPlayersList() {
        $client = static::createClient();

        $client->request('GET', '/players');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
