<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;

use AppBundle\Entity\Attachment;
use AppBundle\Entity\Publication;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends FOSRestController
{

    public function putAction()
    {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $user = $ApiSvc->getUser();

        $attRepo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        $attTypeRepo = $this->getDoctrine()->getRepository('AppBundle:AttachmentType');

        foreach($ApiSvc->getRequestContent() as $attachment) {
            $att = $attRepo->getNew([
                'name' => $attachment['name'],
                'author' => $user,
                'type' => $attTypeRepo->findOneByCode($attachment['type']),
                'body' => $attachment['body'],
            ]);
            $em->persist($att);
        }
        $em->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getAction() {
        $ApiSvc = $this->container->get('api.service');
        $user = $ApiSvc->getUser();

        $view = $this->view($user->getAttachments(), Response::HTTP_OK);
        return $this->handleView($view);
    }

    public function deleteAction() {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        $user = $ApiSvc->getUser();

        foreach($ApiSvc->getRequestContent() as $attId) {
            $pub = $repository->findOneBy([
                'id' => (int) $attId,
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