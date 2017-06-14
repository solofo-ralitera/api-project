<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\Attachment;
use AppBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AttachmentController extends FOSRestController implements ClassResourceInterface
{

    public function cgetAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        return (new ArrayCollection($repo->findAll()))->map(function($item) {
            return $item->toArray();
        });
    }

    public function getAction(Attachment $attachment)
    {
        switch($attachment->getType()->getCode() == 'PCT') {
            case 'PCT':
                return $this->get('api.attachement')->download($attachment);
            default:
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

    public function postAction(Request $request) : array {
        $attachment = (new Attachment())
            ->setAuthor($this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->find($request->get('author')))
            ->setType($this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:AttachmentType')->find($request->get('type')));

        if (! $this->createForm(\AppBundle\Form\Attachment::class, $attachment)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($attachment);
        $em->flush();
        return $attachment->toArray();
    }
}