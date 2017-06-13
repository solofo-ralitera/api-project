<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Publication;

/**
 * TimeLineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNew(array $publication) {
        return (new Publication())
            ->setStatus($publication['status'])
            ->setAuthor($publication['author'])
            ->setType($publication['type'])
        ;
    }
}
