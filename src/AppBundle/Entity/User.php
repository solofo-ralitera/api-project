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
     * @ORM\OneToOne(targetEntity="Attachment")
     * @ORM\JoinColumn(name="avatar", referencedColumnName="id")
     */
    private $avatar;

    public function __construct()
    {
        parent::__construct();
    }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'last_name' => $this->getLastName(),
            'first_name' => $this->getFirstName(),
            'name' => $this->getLastName() . ' ' . $this->getFirstName(),
            //'avatar' => $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/api/users/'.$this->getId().'/avatar',
            'avatar' => '/users/'.$this->getId().'/avatar',
        ];
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
