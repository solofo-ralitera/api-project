<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;

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

        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

    public function getAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        $view = $this->view(array_map(function($item) {
            return $item->toArray();
        }, $repository->findAll()), Response::HTTP_OK);
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

        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

}