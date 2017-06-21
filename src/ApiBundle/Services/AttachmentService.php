<?php

namespace ApiBundle\Services;

use AppBundle\Entity\Attachment;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AttachmentService
{
    public function download(? Attachment $attachment, string $default='') : Response {
        if($attachment && $parameter = json_decode($attachment->getParameters(), true)) {
            if (isset($parameter['path']) && file_exists($parameter['path'])) {
                $default = $parameter['path'];
            }
        }
        $response = View::create()->getResponse();
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename($default)));
        $response->headers->set('Content-Type', mime_content_type ($default));
        $response->sendHeaders();
        readfile($default);
        return $response;
    }
}