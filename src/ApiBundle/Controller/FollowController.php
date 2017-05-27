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

    public function putFollowersAction()
    {
        $ApiSvc = $this->container->get('api.service');
        $em = $this->getDoctrine()->getManager();
        $follower = $ApiSvc->getUser();
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        foreach($ApiSvc->getRequestContent() as $userId) {
            $follow = new Follow();
            $follow
                ->setFollowing($userRepo->find($userId))
                ->setFollower($follower)
            ;
            $em->persist($follow);
        }
        $em->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    public function getFollowersAction() {
        $ApiSvc = $this->container->get('api.service');
        $user = $ApiSvc->getUser();

        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');

        $view = $this->view($userRepo->getFollowers($user), Response::HTTP_OK);
        return $this->handleView($view);
    }

    public function getFollowingsAction() {
        $ApiSvc = $this->container->get('api.service');
        $user = $ApiSvc->getUser();

        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');

        $view = $this->view($userRepo->getFollowings($user), Response::HTTP_OK);
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