<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;

class CommentsController extends FOSRestController implements ClassResourceInterface
{
    // ...

    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
        return (new ArrayCollection($repo->findAll()))->map(function(Comment $item) {
            return $item->toArray();
        });
    }

    public function getAction(Comment $comment) : array
    {
        return $comment->toArray();
    }

}