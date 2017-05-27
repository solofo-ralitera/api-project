<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AttachmentType
 *
 * @ORM\Table(name="attachment_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttachmentTypeRepository")
 */
class AttachmentType
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=20, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="type")
     */
    private $attachments;

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
     * Set name
     *
     * @param string $name
     *
     * @return AttachmentType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return AttachmentType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * Add attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     *
     * @return AttachmentType
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
}
