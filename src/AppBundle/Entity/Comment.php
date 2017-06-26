<?php

namespace AppBundle\Entity;

use AppBundle\Interfaces\AttachmentEntityInterface;
use AppBundle\Interfaces\CommentEntityInterface;
use AppBundle\Interfaces\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment implements EntityInterface, CommentEntityInterface, AttachmentEntityInterface
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
     * @ORM\Column(name="text", type="string")
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_modification", type="datetime")
     */
    private $dateLastModification;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author", referencedColumnName="id", onDelete="CASCADE")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="Comment", cascade={"persist"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Attachment", cascade={"persist"})
     */
    private $attachments;

    public function toArray() : array
    {
        $parentId = $this->getId();
        return [
            'id' => $parentId,
            'text' => $this->getText(),
            'author' => $this->getAuthor()->toArray(),
            'comments' => ($this->getComments() ?? new ArrayCollection())->map(function(Comment $comment) use($parentId) {
                return $parentId != $comment->getId() ? $comment->toArray() : [];
            })
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
     * Set text
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Comment
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateLastModification
     *
     * @param \DateTime $dateLastModification
     *
     * @return Comment
     */
    public function setDateLastModification($dateLastModification)
    {
        $this->dateLastModification = $dateLastModification;

        return $this;
    }

    /**
     * Get dateLastModification
     *
     * @return \DateTime
     */
    public function getDateLastModification()
    {
        return $this->dateLastModification;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Comment
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->dateLastModification = new \DateTime();
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return PersistentCollection|\Doctrine\Common\Collections\Collection
     */
    public function getComments() : PersistentCollection
    {
        return $this->comments;
    }

    /**
     * Add attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     *
     * @return Comment
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
     * @return PersistentCollection|\Doctrine\Common\Collections\Collection
     */
    public function getAttachments() : PersistentCollection
    {
        return $this->attachments;
    }
}
