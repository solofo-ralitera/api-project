<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\PublicationType;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class PublicationTypesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Get("/publicationtypes")
     */
    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:PublicationType');
        return (new ArrayCollection($repo->findAll()))->map(function(PublicationType $item) {
            return $item->toArray();
        });
    }

    /**
     * @Get("/publicationtypes/{type}")
     * @param PublicationType $type
     * @return array
     */
    public function getAction(PublicationType $type) : array
    {
        return $type->toArray();
    }

    /**
     * @Post("/publicationtypes")
     * @View(statusCode=201)
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request) : array {
        $entity = new PublicationType();
        if (! $this->createForm(\AppBundle\Form\PublicationType::class, $entity)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($entity);
        $em->flush();
        $em->clear();
        unset($em);
        return $entity->toArray();
    }
}