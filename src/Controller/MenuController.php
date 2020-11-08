<?php

namespace App\Controller;

use App\Entity\Ardoise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    //pas de route parce que c'est une vue partielle
    //appellée par {{render(controller("App\\Controller\\MenuController::menu")) }}
    //dans base.html.twig
    public function menu()
    {
        $repository=$this->getDoctrine()->getRepository(Ardoise::class);

        $ardoises=$repository->findAll();

        return $this->render('menu/_menu.html.twig', [
            'ardoises'=>$ardoises,

        ]);
    }
}
