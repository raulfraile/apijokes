<?php

namespace RaulFraile\ApiJokesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use RaulFraile\ApiJokesBundle\Entity\Joke;

class ApiController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jokes = $em->getRepository('RaulFraileApiJokesBundle:Joke')->findAll();

        $data = array_map(function ($item) {
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

        $em = $this->getDoctrine()->getManager();

        // validate input (don't allow jokes about java)
        $content = $request->request->get('content');
        if (stripos($content, 'java') !== false) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Java jokes are not allowed');
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

        $this->get('mailer')->send($message);

        return new JsonResponse(array(
            'id' => $joke->getId(),
            'content' => $joke->getContent()
        ));
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
        if (stripos($content, 'java') !== false) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Java jokes are not allowed');
        }

        $jokesRepository = $em->getRepository('RaulFraileApiJokesBundle:Joke');

        // get the object
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

        $this->get('mailer')->send($message);

        return new JsonResponse(array(
            'id' => $joke->getId(),
            'content' => $joke->getContent()
        ));
    }
}
