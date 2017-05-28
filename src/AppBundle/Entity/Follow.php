<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Follow
 *
 * @ORM\Table(name="follow")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FollowRepository")
 */
class Follow
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User",  inversedBy="followings")
     * @ORM\JoinColumn(name="following", referencedColumnName="id")
     */
    private $following;

    /**
     * @ORM\ManyToOne(targetEntity="User",  inversedBy="followers")
     * @ORM\JoinColumn(name="follower", referencedColumnName="id")
     */
    private $follower;

    public function toArray() {
        return [
            'following' => $this->getFollowing()->toArray(),
            'follower' => $this->getFollower()->toArray(),
        ];
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set following
     *
     * @param \AppBundle\Entity\User $following
     *
     * @return Follow
     */
    public function setFollowing(User $following = null)
    {
        $this->following = $following;

        return $this;
    }

    /**
     * Get following
     *
     * @return \AppBundle\Entity\User
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Set follower
     *
     * @param \AppBundle\Entity\User $follower
     *
     * @return Follow
     */
    public function setFollower(User $follower = null)
    {
        $this->follower = $follower;

        return $this;
    }

    /**
     * Get follower
     *
     * @return \AppBundle\Entity\User
     */
    public function getFollower()
    {
        return $this->follower;
    }
}
