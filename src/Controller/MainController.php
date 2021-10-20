<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Auteur;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

     /**
     * @Route("/aboutus", name="about_us")
     */
    public function aboutus(): Response
    {
        $data=file_get_contents("../data/team.json");
        $tabAuteurs=json_decode($data, true);

        return $this->render('main/aboutus.html.twig', [
            'controller_name' => 'MainController',
            'tabAuteurs'=>$tabAuteurs,

        ]);
    }
}
