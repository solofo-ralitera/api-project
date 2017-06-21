<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Publication;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PublicationsController extends FOSRestController implements ClassResourceInterface
{

    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Publication');
        return (new ArrayCollection($repo->findAll()))->map(function(Publication $item) {
            return $item->toArray();
        });
    }

    public function getAction(Publication $publication) : array
    {
        return $publication->toArray();
    }

    /**
     * @View(statusCode=201)
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request) : array {
        $entity = new Publication();
        if (! $this->createForm(\AppBundle\Form\Publication::class, $entity)->submit($request->request->all())->isValid())
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
        return $this->getDoctrine()->getRepository('AppBundle:Publication')
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
        $em->persist($this->getDoctrine()->getRepository('AppBundle:Publication')
            ->find($id)
            ->addComment($comment));
        $em->flush();
        $em->clear();
        unset($em);
        return $comment->toArray();
    }
}