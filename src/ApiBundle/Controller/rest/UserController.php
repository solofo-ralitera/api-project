<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class UserController extends FOSRestController implements ClassResourceInterface
{
    // ...

    public function cgetAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        return (new ArrayCollection($repo->findAll()))->map(function(User $item) {
            return $item->toArray();
        });
    }

    public function getAction(int $id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:User')
            ->find($id)->toArray();
    }

    public function getFollowersAction(int $userId)
    {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        if($u = $userRepo->find($userId)) {
            return $this->getDoctrine()->getRepository('AppBundle:Follow')
                ->getFollowers($u);
        }
    }

    public function getFollowingsAction(int $userId)
    {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        if($u = $userRepo->find($userId)) {
            return $this->getDoctrine()->getRepository('AppBundle:Follow')
                ->getFollowings($u);
        }
    }

    public function getAvatarAction(int $userId)
    {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $image = $this->container->getParameter('api.user.avatar.path') . 'default.png';
        if($u = $userRepo->find($userId)) {
            if($avatar = $u->getAvatar()) {
                $parameter = json_decode($avatar->getParameters(), true);
                if($parameter && isset($parameter['path']) && file_exists($parameter['path'])) {
                    $image = $parameter['path'];
                }
            }
        }
        $response = $this->view()->getResponse();
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, 'avatar.png'));
        $response->headers->set('Content-Type', 'image/png');
        $response->sendHeaders();
        readfile($image);
        return $response;
    }
}