<?php

namespace AppBundle\Entity;

use AppBundle\Interfaces\AttachmentEntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Interfaces\CommentEntityInterface;
use Doctrine\ORM\PersistentCollection;

/**
 * TimeLine
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PublicationRepository")
 */
class Publication implements CommentEntityInterface, AttachmentEntityInterface
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="PublicationType")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=6)
     */
    private $lang;

    /**
     * @ORM\ManyToMany(targetEntity="Attachment", cascade={"persist"})
     */
    private $attachments;

    /**
     * @ORM\ManyToMany(targetEntity="Comment", cascade={"persist"})
     */
    private $comments;

    public function __construct()
    {
        $this->lang = 'mg-MG';
        $this->date = new \DateTime();
    }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'content' => $this->getContent(),
            'date' => $this->getDate(),
            'author' => $this->getAuthor()->toArray(),
            'attachments' => ($this->getAttachments() ?? new ArrayCollection())->map(function(Attachment $item) {
                return $item->toArray();
            }),
            'comment' => ($this->getComments() ?? new ArrayCollection())->map(function(Comment $item) {
                return $item->toArray();
            }),
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
     * Set lang
     *
     * @param string $lang
     *
     * @return Publication
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
     * Set type
     *
     * @param \AppBundle\Entity\PublicationType $type
     *
     * @return Publication
     */
    public function setType(PublicationType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\PublicationType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Publication
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Publication
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add attachment
     *
     * @param \AppBundle\Entity\Attachment $attachment
     *
     * @return Publication
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

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Publication
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
     * Set content
     *
     * @param string $content
     *
     * @return Publication
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
