<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande', name: 'app_commande_')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        // Recherches de toutes les commandes de l'utilisateur connecté et tri des commandes par ordre decroissant
        $commandes = $commandeRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC']);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
