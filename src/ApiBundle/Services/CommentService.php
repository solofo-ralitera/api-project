<?php

namespace ApiBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentService
{
    private $em;
    private $requestStack;

    public function __construct(
        RequestStack $requestStack,
        EntityManager $entityManager
    )
    {
        $this->em = $entityManager;
        $this->requestStack = $requestStack;
    }

}