<?php

namespace RaulFraile\ApiJokesBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use RaulFraile\ApiJokesBundle\Entity\Joke;

class JokeEmailer
{

    /** @var $mailer \Swift_Mailer */
    protected $mailer;
    protected $templating;
    protected $emailFrom;
    protected $emailTo;

    public function __construct($mailer, $templating, $emailFrom, $emailTo)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailFrom = $emailFrom;
        $this->emailTo = $emailTo;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Joke) {
            $message = $this->getMessage('Joke created', 'joke_created', $entity);

            $this->mailer->send($message);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Joke) {
            $message = $this->getMessage('Joke updated', 'joke_updated', $entity);

            $this->mailer->send($message);
        }
    }

    protected function getMessage($subject, $template, Joke $joke)
    {
        return \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->emailFrom)
            ->setTo($this->emailTo)
            ->setBody($this->templating->render(
                sprintf('RaulFraileApiJokesBundle:Email:%s.txt.twig', $template),
                array('joke' => $joke))
        );
    }
}