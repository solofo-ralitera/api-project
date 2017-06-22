<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\Attachment;
use AppBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommentsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @return ArrayCollection
     */
    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
        return (new ArrayCollection($repo->findAll()))->map(function(Comment $item) {
            return $item->toArray();
        });
    }

    /**
     * @param Comment $comment
     * @return array
     */
    public function getAction(Comment $comment) : array
    {
        return $comment->toArray();
    }

    /**
     * @View(statusCode=201)
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request) : array {
        $entity = new Comment();
        if (! $this->createForm(\AppBundle\Form\Comment::class, $entity)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($entity);
        $em->flush();
        $em->clear();
        unset($em);
        return $entity->toArray();
    }

    /**
     * @param int $id
     * @return ArrayCollection
     */
    public function getCommentsAction(int $id) : ArrayCollection
    {
        return $this->getDoctrine()->getRepository('AppBundle:Comment')->getEntityComments('AppBundle:Comment', $id);
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
        return $this->getDoctrine()->getRepository('AppBundle:Comment')->addEntityComment($comment, 'AppBundle:Comment', $id);
    }

    /**
     * @param int $id
     * @return ArrayCollection
     */
    public function getAttachmentsAction(int $id) : ArrayCollection
    {
        return $this->getDoctrine()->getRepository('AppBundle:Attachment')->getEntityAttachments('AppBundle:Comment', $id);
    }

    /**
     * @View(statusCode=201)
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function postAttachmentsAction(Request $request, int $id) : array
    {
        $attachment = new Attachment();
        if (! $this->createForm(\AppBundle\Form\Attachment::class, $attachment)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();
        return $this->getDoctrine()->getRepository('AppBundle:Attachment')->addEntityAttachment($attachment, 'AppBundle:Comment', $id);
    }
}