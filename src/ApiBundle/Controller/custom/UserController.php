<?php

namespace ApiBundle\Controller\custom;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController
{
    public function putAction()
    {
        $ApiSvc = $this->container->get('api.service');
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $userManager = $this->container->get('fos_user.user_manager');

        foreach($ApiSvc->getRequestContent() as $user) {
            if (empty($userRepo->findOneByUsername($user['name']))) {
                $u = $userManager->createUser()
                    ->setUsername($user['name'])
                    ->setEmail($user['email'])
                    ->setEmailCanonical($user['email'])
                    ->setLang('mg-MG')
                    ->setEnabled(1)
                    ->setRoles(array(User::ROLE_DEFAULT))
                    ->setPlainPassword($user['password'])
                ;
                $userManager->updateUser($u);
            }
        }
        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

    public function getAction() {
        $ApiSvc = $this->container->get('api.service');
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $return = array();
        if(in_array('ROLE_SUPER_ADMIN', $ApiSvc->getUser()->getRoles())) {
            $return['data'] = array_map(function($item) {
                return $item->toArray();
            }, $userRepo->findAll());
        }

        return $this->handleView(
            $this->view($return, Response::HTTP_OK)
        );
    }
}