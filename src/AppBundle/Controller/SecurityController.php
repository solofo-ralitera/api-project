<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\ExecutionContextInterface;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {



    }

    public function logoutAction(Request $request)
    {
        /*
        $this->em()->flush();
        $this->em()->clear();
        $this->getDoctrine()->getConnection()->close();
        */
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
}
