<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{


    /**
     * @Route("/hello/{prenom}", name="hello_prenom")
     * @Route("/hello", name="hello_base")
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * Montre la page qui dit bonjour
     */
    public function hello($prenom = "anonyme", $age = 0) {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo)
    {
        return $this->render(
            'home.html.twig',
            [
                'ads' => $adRepo->findBestAds(3),
                'users' => $userRepo->findBestUsers()
            ]
        );
    }


}

