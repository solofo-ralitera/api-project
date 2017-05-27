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
    public function setFollowing(\AppBundle\Entity\User $following = null)
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
    public function setFollower(\AppBundle\Entity\User $follower = null)
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
