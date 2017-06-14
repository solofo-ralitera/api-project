<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\AttachmentType;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AttachmentTypeController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Get("/attachmenttypes")
     */
    public function cgetAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:AttachmentType');
        return (new ArrayCollection($repo->findAll()))->map(function($item) {
            return $item->toArray();
        });
    }

    /**
     * @Get("/attachmenttypes/{attachmentType}")
     * @param AttachmentType $attachmentType
     * @return array
     */
    public function getAction(AttachmentType $attachmentType) : array
    {
        return $attachmentType->toArray();
    }

    /**
     * @Post("/attachmenttypes")
     * @View(statusCode=201)
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request) : array
    {
        $attachmentType = new AttachmentType();
        if (! $this->createForm(\AppBundle\Form\AttachmentType::class, $attachmentType)->submit($request->request->all())->isValid())
            throw new BadRequestHttpException();

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($attachmentType);
        $em->flush();
        return $attachmentType->toArray();
    }
}