<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavController extends AbstractController
{
    #[Route('/nav', name: 'app_nav')]
    public function index(CategorieRepository $categorieRepository): Response
    {   
        //Afin de pouvoir gerer de façon dynamique les categories dans la nav 

        $categories = $categorieRepository->findAll();
        return $this->render('nav/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
