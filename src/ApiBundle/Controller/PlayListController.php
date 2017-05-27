<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;


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
        $view = $this->view($playlist, Response::HTTP_OK);

        return $this->handleView($view);
    }

    public function setAction()
    {

        $view = $this->view([], Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }
}