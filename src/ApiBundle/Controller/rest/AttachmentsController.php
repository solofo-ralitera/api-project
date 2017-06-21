<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\{Attachment, Comment};
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;

class AttachmentsController extends FOSRestController implements ClassResourceInterface
{

    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        return (new ArrayCollection($repo->findAll()))->map(function(Attachment $item) {
            return $item->toArray();
        });
    }

    public function getAction(Attachment $attachment)
    {
        switch($attachment->getType()->getCode()) {
            case 'PCT':
                return $this->get('api.attachement')->download($attachment);
            default:
                return $attachment->toArray();
        }
    }

    /**
     * @View(statusCode=201)
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request) : array {
        $entity = new Attachment();
        if (! $this->createForm(\AppBundle\Form\Attachment::class, $entity)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($entity);
        $em->flush();
        $em->clear();
        unset($em);
        return $entity->toArray();
    }

    public function getCommentsAction(int $id) : ArrayCollection
    {
        return $this->getDoctrine()->getRepository('AppBundle:Attachment')
            ->find($id)
            ->getComments()
            ->map(function(Comment $item) {
                return $item->toArray();
            });
    }

    /**
     * @View(statusCode=201)
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function postCommentsAction(Request $request, int $id) : array
    {
        $comment = new Comment();
        if (! $this->createForm(\AppBundle\Form\Comment::class, $comment)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($this->getDoctrine()->getRepository('AppBundle:Attachment')
            ->find($id)
            ->addComment($comment));
        $em->flush();
        $em->clear();
        unset($em);
        return $comment->toArray();
    }
}