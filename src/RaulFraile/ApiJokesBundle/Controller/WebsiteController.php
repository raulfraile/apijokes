<?php

namespace RaulFraile\ApiJokesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WebsiteController extends Controller {
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $jokes = $em->getRepository('RaulFraileApiJokesBundle:Joke')->findAll();
        return $this->render('RaulFraileApiJokesBundle:Website:index.html.twig', array(
            'jokes' => $jokes
        ));
    }
}
