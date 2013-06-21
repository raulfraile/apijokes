<?php

namespace RaulFraile\ApiJokesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use RaulFraile\ApiJokesBundle\Entity\Joke;
use RaulFraile\ApiJokesBundle\Validator\Constraints\ContainsJava;

class ApiController extends BaseController
{
    public function listAction()
    {
        $em = $this->getEntityManager();

        $jokes = $em->getRepository('RaulFraileApiJokesBundle:Joke')->findAll();

        $data = array_map(function (Joke $item) {
            return array(
                'id' => $item->getId(),
                'content' => $item->getContent()
            );
        }, $jokes);

        return new JsonResponse($data);
    }

    public function addAction()
    {
        $request = $this->getRequest();

        // check that the HTTP method is POST
        if (!$request->isMethod('POST')) {
            throw new \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException(array('POST'));
        }

        $em = $this->getEntityManager();

        // validate input (don't allow jokes about java)
        $content = $request->request->get('content');
        $errorList = $this->getValidator()->validateValue($content, new ContainsJava());
        if ($errorList->count() > 0) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($errorList->get(0)->getMessage());
        }

        // create the object
        $joke = new Joke();
        $joke->setContent($content);

        // persist
        $em->persist($joke);
        $em->flush();

        // send email
        $message = \Swift_Message::newInstance()
            ->setSubject('Joke created')
            ->setFrom($this->container->getParameter('raulfraile_apijokes_email_from'))
            ->setTo($this->container->getParameter('raulfraile_apijokes_email_to'))
            ->setBody($this->renderView(
                'RaulFraileApiJokesBundle:Email:joke_created.txt.twig',
                array('joke' => $joke))
        );

        $mailer = $this->getMailer();
        $mailer->send($message);

        return new JsonResponse(array(
            'id' => $joke->getId(),
            'content' => $joke->getContent()
        ), 201);
    }

    public function editAction()
    {
        $request = $this->getRequest();

        // check that the HTTP method is POST
        if (!$request->isMethod('POST')) {
            throw new \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException(array('POST'));
        }

        $em = $this->getDoctrine()->getManager();

        // validate input (don't allow jokes about java)
        $content = $request->request->get('content');
        $errorList = $this->getValidator()->validateValue($content, new ContainsJava());
        if ($errorList->count() > 0) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($errorList->get(0)->getMessage());
        }

        $jokesRepository = $em->getRepository('RaulFraileApiJokesBundle:Joke');

        // get the object

        /** @var $joke Joke */
        $joke = $jokesRepository->find($request->request->get('id'));

        if (null === $joke) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Id not found');
        }

        // edit the object
        $joke->setContent($content);

        // persist
        $em->persist($joke);
        $em->flush();

        // send email
        $message = \Swift_Message::newInstance()
            ->setSubject('Joke updated')
            ->setFrom($this->container->getParameter('raulfraile_apijokes_email_from'))
            ->setTo($this->container->getParameter('raulfraile_apijokes_email_to'))
            ->setBody($this->renderView(
                'RaulFraileApiJokesBundle:Email:joke_updated.txt.twig',
                array('joke' => $joke))
        );

        $mailer = $this->getMailer();
        $mailer->send($message);

        return new JsonResponse(array(
            'id' => $joke->getId(),
            'content' => $joke->getContent()
        ), 204);
    }
}
