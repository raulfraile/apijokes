<?php

namespace RaulFraile\ApiJokesBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebsiteTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
