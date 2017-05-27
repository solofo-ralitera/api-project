<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;


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
        $view = $this->view(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}