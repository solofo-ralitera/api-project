<?php

namespace AppBundle\Interfaces;

use AppBundle\Entity\Attachment;
use Doctrine\ORM\PersistentCollection;

interface AttachmentEntityInterface
{
    public function addAttachment(Attachment $attachment);
    public function removeAttachment(Attachment $attachment);
    public function getAttachments() : PersistentCollection;
}