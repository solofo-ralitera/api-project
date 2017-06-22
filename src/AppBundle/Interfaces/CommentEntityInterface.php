<?php

namespace AppBundle\Interfaces;

use AppBundle\Entity\Comment;
use Doctrine\ORM\PersistentCollection;

interface CommentEntityInterface
{
    public function addComment(Comment $comment);
    public function removeComment(Comment $comment);
    public function getComments() : PersistentCollection;
}