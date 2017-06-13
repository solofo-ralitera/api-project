<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\Publication;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;

class PublicationsController extends FOSRestController implements ClassResourceInterface
{
    // ...

    public function cgetAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Publication');
        return (new ArrayCollection($repo->findAll()))->map(function($item) {
            return $item->toArray();
        });
    }

    public function getAction(Publication $publication)
    {
        return $publication->toArray();
    }

}