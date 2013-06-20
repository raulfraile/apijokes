<?php

namespace RaulFraile\ApiJokesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Joke entity
 *
 * @ORM\Table(name="joke")
 * @ORM\Entity
 */
class Joke
{
    /**
     * Joke id
     *
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="string")
     */
    protected $content;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
