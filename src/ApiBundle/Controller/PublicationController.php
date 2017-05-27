<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;

use AppBundle\Entity\Publication;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class PublicationController extends FOSRestController
{

    public function putAction()
    {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $user = $ApiSvc->getUser();
        $publicationType = $em->getRepository('AppBundle:PublicationType')->find(1);
        foreach($ApiSvc->getRequestContent() as $publication) {
            $pub = new Publication();
            $pub
                ->setStatus($publication['status'])
                ->setAuthor($user)
                ->setType($publicationType)
            ;
            $em->persist($pub);
        }
        $em->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getAction() {
        $ApiSvc = $this->container->get('api.service');
        $user = $ApiSvc->getUser();

        $view = $this->view($user->getPublications(), Response::HTTP_OK);
        return $this->handleView($view);
    }

    public function deleteAction() {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Publication');
        $user = $ApiSvc->getUser();

        foreach($ApiSvc->getRequestContent() as $pubId) {
            $pub = $repository->findOneBy([
                'id' => (int) $pubId,
                'author' => $user->getId(),
            ]);
            if(! empty($pub)) {
                $em->remove($pub);
            }
        }
        $em->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}