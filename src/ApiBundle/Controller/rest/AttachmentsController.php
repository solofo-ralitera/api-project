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

    /**
     * @return ArrayCollection
     */
    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        return (new ArrayCollection($repo->findAll()))->map(function(Attachment $item) {
            return $item->toArray();
        });
    }

    /**
     * @param Attachment $attachment
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @param int $id
     * @return ArrayCollection
     */
    public function getCommentsAction(int $id) : ArrayCollection
    {
        return $this->get('api.comment')->getEntityComments('AppBundle:Attachment', $id);
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
        return $this->get('api.comment')->addEntityComment($comment, 'AppBundle:Attachment', $id);
    }
}