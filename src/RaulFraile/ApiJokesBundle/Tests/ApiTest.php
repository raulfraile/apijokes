<?php

namespace RaulFraile\ApiJokesBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/list');

        $response = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(8, count($response));
    }

    public function testAdd()
    {
        $content = 'test';

        $client = static::createClient();
        $crawler = $client->request('POST', '/api/add', array('content' => $content));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('content', $response);
        $this->assertEquals($content, $response['content']);

        if ($profile = $client->getProfile()) {
            $this->assertEquals(1, $profile->getCollector('swiftmailer')->getMessageCount());
        }
    }

    public function testAddJavaJoke()
    {
        $content = 'Java is so fast!';

        $client = static::createClient();
        $crawler = $client->request('POST', '/api/add', array('content' => $content));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        if ($profile = $client->getProfile()) {
            $this->assertEquals(0, $profile->getCollector('swiftmailer')->getMessageCount());
        }
    }

    public function testEdit()
    {
        $newContent = 'test_edit';

        $client = static::createClient();
        $crawler = $client->request('POST', '/api/edit', array(
            'id' => 1,
            'content' => $newContent
        ));

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        if ($profile = $client->getProfile()) {
            $this->assertEquals(1, $profile->getCollector('swiftmailer')->getMessageCount());
        }
    }
}
