<?php

namespace ApiBundle\Controller\rest;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class UserController extends Controller implements ClassResourceInterface
{
    // ...

    public function cgetAction()
    {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        return (new ArrayCollection($userRepo->findAll()))->map(function($item) {
            return $item->toArray();
        });
    }

    public function getAction($id)
    {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        return $userRepo->find($id)->toArray();
    }
}