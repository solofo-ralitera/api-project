<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function getFollowers(User $user) {
        $return = [];
        foreach($user->getFollowings() as $follow) {
            $return[] = $follow->getFollower()->toArray();
        }
        return $return;
    }

    public function getFollowings(User $user) {
        $return = [];
        foreach($user->getFollowers() as $follow) {
            $return[] = $follow->getFollowing()->toArray();
        }
        return $return;
    }

}
