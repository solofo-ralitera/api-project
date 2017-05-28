<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;

use AppBundle\Entity\PublicationType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class PublicationTypeController extends FOSRestController
{

    public function putAction()
    {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:PublicationType');

        foreach($ApiSvc->getRequestContent() as $name) {
            if(empty($repository->findOneByName($name))) {
                $pubType = new PublicationType();
                $pubType->setName($name);
                $em->persist($pubType);
            }
        }
        $em->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:PublicationType');
        $view = $this->view(array_map(function($item) {
            return $item->toArray();
        }, $repository->findAll()), Response::HTTP_OK);
        return $this->handleView($view);
    }

    public function deleteAction() {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:PublicationType');

        foreach($ApiSvc->getRequestContent() as $type) {
            if($pubType = $repository->find($type)) {
                $em->remove($pubType);
            }
        }
        $em->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}