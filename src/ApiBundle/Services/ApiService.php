<?php

namespace ApiBundle\Services;

use AppBundle\Entity\AttachmentType;
use AppBundle\Entity\PublicationType;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Faker\Factory;

class ApiService
{
    protected $requestStack;
    protected $tokenStorage;
    protected $em;

    public function __construct(
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        EntityManager $entityManager
    )
    {
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->em = $entityManager;
    }

    public function init(array $options = [
        'fakeuser' => 30
    ]) {

        // Insert publication type
        $publications = [
            ['Timeline', 'TML'],
            ['Comment', 'CMT'],
        ];
        $puRepo = $this->em->getRepository('AppBundle:PublicationType');
        foreach ($publications as $publication) {
            if (empty($puRepo->findOneByCode($publication[1]))) {
                $pubType = new PublicationType();
                $pubType->setName($publication[0]);
                $pubType->setCode($publication[1]);
                $this->em->persist($pubType);
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
        $attRepo = $this->em->getRepository('AppBundle:AttachmentType');
        foreach ($attachments as $attachment) {
            if(empty($attRepo->findOneByCode($attachment[1]))) {
                $attachmentType = new AttachmentType();
                $attachmentType->setName($attachment[0]);
                $attachmentType->setCode($attachment[1]);
                $this->em->persist($attachmentType);
            }
        }

        $this->em->flush();
        $this->em->clear();

        $userManager = $options['userManager'];
        $userRepo = $this->em->getRepository('AppBundle:User');
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

        // Add fake user
        $faker = Factory::create();
        for ($i = 0; $i < $options['fakeuser']; $i++) {
            $mail = $faker->email;
            $name = $faker->userName;
            $user = $userManager->createUser();
            $user->setUsername($name);
            $user->setEmail($mail);
            $user->setEmailCanonical($mail);
            $user->setLang('mg-MG');
            $user->setEnabled(1);
            $user->setRoles( array(User::ROLE_DEFAULT) ) ;
            $user->setPlainPassword($name);

            $userManager->updateUser($user);
        }
    }

    public function getUser(): ?User {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof JWTUserToken) {
            return $token->getUser();
        } else {
            return null;
        }
    }

    public function getRequestContent(): array {
        $request = $this->requestStack->getCurrentRequest();
        return json_decode($request->getContent(), true);
    }

    public function getUploadedFile(): string {
        // TODO : cas if file sent in form
        return $this->requestStack->getCurrentRequest()->getContent();
    }
}