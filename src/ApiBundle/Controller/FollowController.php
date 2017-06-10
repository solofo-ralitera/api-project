<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;

use AppBundle\Entity\Follow;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class FollowController extends FOSRestController
{

    public function putFollowersAction(int $user)
    {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        if($follower = $userRepo->find($user)) {
            foreach ($ApiSvc->getRequestContent() as $userId) {
                $follow = new Follow();
                $follow
                    ->setFollowing($userRepo->find($userId))
                    ->setFollower($follower);
                $em->persist($follow);
            }
            $em->flush();
        }
        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

    public function getFollowersAction(int $user) {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $followRepo = $this->getDoctrine()->getRepository('AppBundle:Follow');
        $return = array();
        if($u = $userRepo->find($user)) {
            $return['data'] = $followRepo->getFollowers($u);
        }
        return $this->handleView(
            $this->view($return, Response::HTTP_OK)
        );
    }

    public function getFollowingsAction(int $user) {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $followRepo = $this->getDoctrine()->getRepository('AppBundle:Follow');
        $return = array();
        if($u = $userRepo->find($user)) {
            $return = $followRepo->getFollowings($u);
        }
        return $this->handleView($this->view($return, Response::HTTP_OK));
    }

    public function deleteFollowingsAction(int $user) {
        $ApiSvc = $this->container->get('api.service');
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $followRepo = $this->getDoctrine()->getRepository('AppBundle:Follow');
        $em = $this->getDoctrine()->getManager();
        if($follower = $userRepo->find($user)) {
            foreach ($ApiSvc->getRequestContent() as $userId) {
                $folls = $followRepo->findBy([
                    'following' => $userId,
                    'follower' => $follower,
                ]);
                if(! empty($folls)) {
                    foreach ($folls as $foll) {
                        $em->remove($foll);
                    }
                }
                unset($folls);
            }
            $em->flush();
        }
        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

}