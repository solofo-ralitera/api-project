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

class PublicationController extends FOSRestController
{

    public function putAction()
    {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $user = $ApiSvc->getUser();

        $pubTypeRepo = $this->getDoctrine()->getRepository('AppBundle:PublicationType');
        $pubRepo = $this->getDoctrine()->getRepository('AppBundle:Publication');
        $attRepo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        $attTypeRepo = $this->getDoctrine()->getRepository('AppBundle:AttachmentType');

        foreach($ApiSvc->getRequestContent() as $publication) {
            $pub = $pubRepo->getNew([
                'status' => $publication['status'],
                'author' => $user,
                'type' => $pubTypeRepo->findOneByCode($publication['type']),
            ]);

            // Attachments
            if(! empty($publication['attachments'])) {
                foreach ($publication['attachments'] as $attachment) {
                    $att = $attRepo->getNew([
                        'name' => $attachment['name'],
                        'author' => $user,
                        'type' => $attTypeRepo->findOneByCode($attachment['type']),
                        'body' => $attachment['body'],
                    ]);
                    $pub->addAttachment($att);
                    $em->persist($att);
                }
            }
            $em->persist($pub);
        }
        $em->flush();

        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

    public function getAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Publication');
        $view = $this->view(array_map(function($item) {
            return $item->toArray();
        }, $repository->findAll()), Response::HTTP_OK);
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

        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

}