<?php

namespace ApiBundle\Controller\custom;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FOSRestController
{
    public function indexAction()
    {
        $view = $this->view([
            'success' => true,
        ], Response::HTTP_OK);

        return $this->handleView($view);
    }

}