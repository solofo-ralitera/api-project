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
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getFollowersAction(int $user) {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $return = array();
        if($u = $userRepo->find($user)) {
            $return['data'] = $userRepo->getFollowers($u);
        }
        $view = $this->view($return, Response::HTTP_OK);
        return $this->handleView($view);
    }

    public function getFollowingsAction(int $user) {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $return = array();
        if($u = $userRepo->find($user)) {
            $return = $userRepo->getFollowings($u);
        }
        $view = $this->view($return, Response::HTTP_OK);
        return $this->handleView($view);
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
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}