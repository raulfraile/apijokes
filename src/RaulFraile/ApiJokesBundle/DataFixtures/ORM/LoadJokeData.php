<?php

namespace RaulFraile\ApiJokesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Yaml\Yaml;
use RaulFraile\ApiJokesBundle\Entity\Joke;

class LoadJokeData implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $jokes = Yaml::parse(__DIR__ . '/fixtures/joke.yml');

        foreach ($jokes['jokes'] as $item) {
            $joke = new Joke();
            $joke->setContent($item);

            $manager->persist($joke);
        }

        $manager->flush();
    }
}
