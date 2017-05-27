<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AttachmentType;
use AppBundle\Entity\PublicationType;
use FOS\UserBundle\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    public function initAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // Insert publication type
        $publications = [
            ['Timeline', 'TML'],
            ['Comment', 'CMT'],
        ];
        $puRepo = $this->getDoctrine()->getRepository('AppBundle:PublicationType');
        foreach ($publications as $publication) {
            if (empty($puRepo->findOneByCode($publication[1]))) {
                $pubType = new PublicationType();
                $pubType->setName($publication[0]);
                $pubType->setCode($publication[1]);
                $em->persist($pubType);
            }
        }

        // Insert attachement type
        $attachments = [
            ['Text', 'TXT'],
            ['Link', 'LNK'],
            ['Picture', 'PCT'],
            ['Audio', 'AUD'],
            ['Video', 'VID'],
        ];
        $attRepo = $this->getDoctrine()->getRepository('AppBundle:AttachmentType');
        foreach ($attachments as $attachment) {
            if(empty($attRepo->findOneByCode($attachment[1]))) {
                $attachmentType = new AttachmentType();
                $attachmentType->setName($attachment[0]);
                $attachmentType->setCode($attachment[1]);
                $em->persist($attachmentType);
            }
        }

        $em->flush();
        $em->clear();

        $userManager = $this->container->get('fos_user.user_manager');
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        if(empty($userRepo->findOneByUsername('admin'))) {
            $user = $userManager->createUser();
            $user->setUsername('admin');
            $user->setEmail('admin@blabla.com');
            $user->setEmailCanonical('admin@blabla.com');
            $user->setLang('mg-MG');
            $user->setEnabled(1);
            $user->setSuperAdmin(true) ;
            $user->setPlainPassword('admin');

            $userManager->updateUser($user);
        }
        if(empty($userRepo->findOneByUsername('demo'))) {
            $user = $userManager->createUser();
            $user->setUsername('demo');
            $user->setEmail('demo@blabla.com');
            $user->setEmailCanonical('demo@blabla.com');
            $user->setLang('mg-MG');
            $user->setEnabled(1);
            $user->setRoles( array(User::ROLE_DEFAULT) ) ;
            $user->setPlainPassword('demo');

            $userManager->updateUser($user);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
