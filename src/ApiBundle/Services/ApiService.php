<?php

namespace ApiBundle\Services;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    public function getUser()
    {
        $repository = $this->em->getRepository('AppBundle:User');
        return $repository->findOneBy([
            'username' => 'admin'
        ]);
        /*
        $token = $this->tokenStorage->getToken();
        if ($token instanceof JWTUserToken) {
            return $token->getUser();
        } else {
            return null;
        }
        */
    }

    public function getRequestContent() {
        $request = $this->requestStack->getCurrentRequest();
        return json_decode($request->getContent(), true);
    }

    public function getUploadedFile() {
        // TODO : cas if file sent in form
        return $this->requestStack->getCurrentRequest()->getContent();
    }
}