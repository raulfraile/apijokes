<?php

namespace RaulFraile\ApiJokesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator;
use Doctrine\ORM\EntityManager;
use RaulFraile\ApiJokesBundle\Entity\Joke;

abstract class BaseController extends Controller
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

    /**
     * Get validator
     *
     * @return Validator
     */
    protected function getValidator()
    {
        return $this->get('validator');
    }
}