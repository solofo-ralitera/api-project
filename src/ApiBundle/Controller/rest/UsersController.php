<?php

namespace ApiBundle\Controller\rest;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;

class UsersController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @return ArrayCollection
     */
    public function cgetAction() : ArrayCollection
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        return (new ArrayCollection($repo->findAll()))->map(function(User $item) {
            return $item->toArray();
        });
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAction(User $user) : array
    {
        return $user->toArray();
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getFollowersAction(int $userId) : array
    {
        return $this->getDoctrine()->getRepository('AppBundle:Follow')->getFollowers(
            $this->getDoctrine()->getRepository('AppBundle:User')->find($userId)
        );
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getFollowingsAction(int $userId) : array
    {
        return $this->getDoctrine()->getRepository('AppBundle:Follow')->getFollowings(
            $this->getDoctrine()->getRepository('AppBundle:User')->find($userId)
        );
    }

    /**
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAvatarAction(int $userId)
    {
        return $this->get('api.attachement')->download(
            $this->getDoctrine()->getRepository('AppBundle:User')->find($userId)->getAvatar(),
            $this->container->getParameter('api.user.avatar.path') . 'default.png'
        );
    }
}