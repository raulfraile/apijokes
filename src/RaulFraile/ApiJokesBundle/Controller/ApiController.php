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

        return new JsonResponse(array(
            'id' => $joke->getId(),
            'content' => $joke->getContent()
        ), 201);
    }

    public function editAction()
    {
        $request = $this->getRequest();

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

        return new JsonResponse(array(
            'id' => $joke->getId(),
            'content' => $joke->getContent()
        ), 204);
    }
}
