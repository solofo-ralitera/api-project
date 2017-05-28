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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class UserAvatarController extends FOSRestController
{

    public function putAction(int $user)
    {
        $em = $this->getDoctrine()->getManager();
        $ApiSvc = $this->container->get('api.service');
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $attRepo = $this->getDoctrine()->getRepository('AppBundle:Attachment');
        $attTypeRepo = $this->getDoctrine()->getRepository('AppBundle:AttachmentType');

        if($u = $userRepo->find($user)) {
            if(! ($att = $u->getAvatar())) {
                $att = $attRepo->getNewAvatar();
            }
            $avatarFile = $this->container->getParameter('api.user.avatar.path') . $u->getId() . '.png';
            $att
                ->setName('Avatar')
                ->setAuthor($u)
                ->setDateModified(new \DateTime())
                ->setParameters(json_encode([
                    'path' => $avatarFile,
                ]))
                ->setType($attTypeRepo->findOneByCode('PCT'));
            $u->setAvatar($att);

            // Save file
            // TODO : move to a service
            // TODO : resize
            // TODO : check size before process
            // TODO : throw exeption if error
            // TODO : remove alpha
            // TODO : use Imagick ?
            $img = imagecreatefromstring($ApiSvc->getUploadedFile());
            imagepng($img, $avatarFile);
            imagedestroy($img);

            $em->persist($u);
            $em->persist($att);
            $em->flush();
            $em->clear();
        }

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getAction(int $user) {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $image = $this->container->getParameter('api.user.avatar.path') . 'default.png';
        if($u = $userRepo->find($user)) {
            if($avatar = $u->getAvatar()) {
                $parameter = json_decode($avatar->getParameters(), true);
                if($parameter && isset($parameter['path']) && file_exists($parameter['path'])) {
                    $image = $parameter['path'];
                }
            }
        }
        $response = new Response();
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, 'avatar.png'));
        $response->headers->set('Content-Type', 'image/png');
        $response->sendHeaders();
        readfile($image);
        return $response;
    }
}