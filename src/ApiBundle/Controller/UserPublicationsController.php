<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class UserPublicationsController extends FOSRestController
{

    public function putAction(int $user)
    {
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getAction(int $user) {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $return = array();
        if($u = $userRepo->find($user)) {
            $return = $u->getPublications()->map(function($item) {
                return $item->toArray();
            });
        }
        $view = $this->view($return, Response::HTTP_OK);
        return $this->handleView($view);
    }

    public function deleteAction(int $user) {
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}