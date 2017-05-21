<?php
/**
 * Created by PhpStorm.
 * User: popolos
 * Date: 21/05/2017
 * Time: 19:38
 */

namespace ApiBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayListController extends FOSRestController
{
    public function indexAction(Request $request)
    {
        $view = $this->view([
            [
                "file"  => "track1.mp3",
                "title" => "Track 1",
                "image" => "coverart.jpg"
            ],
            [
                "file"  => "track2.mp3",
                "title" => "Track 2",
                "image" => "coverart.jpg"
            ],
            [
                "file"  => "track3.mp3",
                "title" => "Track 3",
                "image" => "coverart.jpg"
            ]
        ], Response::HTTP_OK);

        return $this->handleView($view);
    }
}