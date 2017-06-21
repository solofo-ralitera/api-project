<?php

namespace ApiBundle\Services;

use AppBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param string $entity
     * @param int $entityId
     * @return ArrayCollection
     */
    public function getEntityComments(string $entity, int $entityId) : ArrayCollection
    {
        return $this->em->getRepository($entity)
            ->find($entityId)
            ->getComments()
            ->map(function(Comment $item) {
                return $item->toArray();
            });
    }

    /**
     * @param Comment $comment
     * @param string $entity
     * @param int $entityId
     * @return array
     */
    public function addEntityComment(Comment $comment,string $entity,  int $entityId) : array
    {
        $this->em->persist($this->em->getRepository($entity)
            ->find($entityId)
            ->addComment($comment));
        $this->em->flush();
        $this->em->clear();
        unset($this->em);
        return $comment->toArray();
    }
}