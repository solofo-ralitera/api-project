<?php

namespace ApiBundle\Services;

use AppBundle\Entity\User;
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

    public function getUser(): User {
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