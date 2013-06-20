<?php

namespace RaulFraile\ApiJokesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManager;
use RaulFraile\ApiJokesBundle\Entity\Joke;

class BaseController extends Controller
{

    /**
     * Get entity manager
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Get mailer
     *
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->get('mailer');
    }
}