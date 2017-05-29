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
     * @ORM\Column(name="last_name", type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    private $firstName;

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

    /**
     * @ORM\OneToOne(targetEntity="Attachment")
     * @ORM\JoinColumn(name="avatar", referencedColumnName="id")
     */
    private $avatar;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->publications = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->followings = new ArrayCollection();
    }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'last_name' => $this->getLastName(),
            'first_name' => $this->getFirstName(),
            // TODO : order lang
            'name' => $this->getLastName() . ' ' . $this->getFirstName(),
            'avatar' => $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/api/users/'.$this->getId().'/avatar',
        ];
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
    public function addFollower(Follow $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \AppBundle\Entity\Follow $follower
     */
    public function removeFollower(Follow $follower)
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

    /**
     * Set avatar
     *
     * @param \AppBundle\Entity\Attachment $avatar
     *
     * @return User
     */
    public function setAvatar(Attachment $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \AppBundle\Entity\Attachment
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
}
