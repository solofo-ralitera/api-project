<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;

class AttachmentController extends FOSRestController implements ClassResourceInterface
{
    // ...

    public function cgetAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        return (new ArrayCollection($repo->findAll()))->map(function($item) {
            return $item->toArray();
        });
    }

    public function getAction(int $id)
    {
        $attachment = $this->getDoctrine()->getRepository('AppBundle:Attachment')->find($id);
        if($attachment->getType()->getCode() == 'PCT') {
            return $this->get('api.attachement')->download($attachment);
        }else {
            return $attachment->toArray();
        }
    }

    public function getCommentsAction(int $id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Attachment')
            ->find($id)
            ->getComments()
            ->map(function(Comment $item) {
                return $item->toArray();
            });
    }
}