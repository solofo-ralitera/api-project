<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;

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

    public function getAction(User $user)
    {
        return $user->toArray();
    }

    public function getFollowersAction(int $userId)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Follow')->getFollowers(
            $this->getDoctrine()->getRepository('AppBundle:User')->find($userId)
        );
    }

    public function getFollowingsAction(int $userId)
    {
        return $this->getDoctrine()->getRepository('AppBundle:Follow')->getFollowings(
            $this->getDoctrine()->getRepository('AppBundle:User')->find($userId)
        );
    }

    public function getAvatarAction(int $userId)
    {
        return $this->get('api.attachement')->download(
            $this->getDoctrine()->getRepository('AppBundle:User')->find($userId)->getAvatar(),
            $this->container->getParameter('api.user.avatar.path') . 'default.png'
        );
    }
}