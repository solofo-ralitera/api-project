<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=6, nullable=true)
     */
    private $lang;

    /**
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="author")
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="author")
     */
    private $attachments;

    /**
     * @ORM\OneToMany(targetEntity="Follow", mappedBy="following")
     */
    private $followings;

    /**
     * @ORM\OneToMany(targetEntity="Follow", mappedBy="follower")
     */
    private $followers;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->publications = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->followings = new ArrayCollection();
    }

    /**
     * Add publication
     *
     * @param \AppBundle\Entity\Publication $publication
     *
     * @return User
     */
    public function addPublication(Publication $publication)
    {
        $this->publications[] = $publication;

        return $this;
    }

    /**
     * Remove publication
     *
     * @param \AppBundle\Entity\Publication $publication
     */
    public function removePublication(Publication $publication)
    {
        $this->publications->removeElement($publication);
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return User
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Add attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     *
     * @return User
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Remove attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     */
    public function removeAttachment(Attachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Add following
     *
     * @param \AppBundle\Entity\Follow $following
     *
     * @return User
     */
    public function addFollowing(\AppBundle\Entity\Follow $following)
    {
        $this->followings[] = $following;

        return $this;
    }

    /**
     * Remove following
     *
     * @param \AppBundle\Entity\Follow $following
     */
    public function removeFollowing(\AppBundle\Entity\Follow $following)
    {
        $this->followings->removeElement($following);
    }

    /**
     * Get followings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowings()
    {
        return $this->followings;
    }

    /**
     * Add follower
     *
     * @param \AppBundle\Entity\Follow $follower
     *
     * @return User
     */
    public function addFollower(\AppBundle\Entity\Follow $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \AppBundle\Entity\Follow $follower
     */
    public function removeFollower(\AppBundle\Entity\Follow $follower)
    {
        $this->followers->removeElement($follower);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }
}
