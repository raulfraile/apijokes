<?php

namespace RaulFraile\ApiJokesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RaulFraile\ApiJokesBundle\Entity\Joke;

class LoadJokeData implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $jokes = array(
            'I would love to change the world, but they won’t give me the source code',
            'There’s no place like 127.0.0.1',
            'If at first you don’t succeed; call it version 1.0',
            'You know it’s love when you memorize her IP number to skip DNS overhead',
            'Beware of programmers that carry screwdrivers',
            'Best file compression around: “rm *.*” = 100% compression',
            'The truth is out there…anybody got the URL?',
            'What color do you want that database?'
        );

        foreach ($jokes as $item) {
            $joke = new Joke();
            $joke->setContent($item);

            $manager->persist($joke);
        }

        $manager->flush();
    }
}
