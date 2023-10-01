<?php

namespace App\Controller;

use App\Repository\MontreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    // #[Route('/', name: 'front')]
    public function index(Request $request): Response
    {
        // Commande qui permet de vider le panier
        // $request->getSession()->set('panier', []);
        
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/', name: 'front')]
    public function liste(MontreRepository $montreRepository): Response
    {
        $montres = $montreRepository->findBy([]);
        return $this->render('montre_front/liste.html.twig', [
            'montres' => $montres,
        ]);
    }
}
