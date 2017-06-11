<?php

namespace ApiBundle\Controller\custom;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class PlayListController extends FOSRestController
{
    public function getAction()
    {
        $rangeArray = range(0, 10);
        $playlist = array();
        foreach($rangeArray as $i) {
            $playlist["track" . $i] = [
                "file"  => "track".$i.".mp3",
                "title" => "Track ".$i."",
                "image" => "coverart.jpg"
            ];
        }
        return $this->handleView(
            $this->view($playlist, Response::HTTP_OK)
        );
    }

    public function setAction()
    {
        return $this->handleView(
            $this->view([], Response::HTTP_NO_CONTENT)
        );
    }
}