<?php

namespace App\Controller;

use App\Entity\Montre;
use App\Repository\MontreCommandeRepository;
use App\Repository\MontreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request, MontreRepository $montreRepository): Response
    {
        $montresId = $request->getSession()->get('panier');
        $paniers = $montreRepository->findBy(['id' => array_keys($montresId)]);
        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'quantites' => $montresId

        ]);
    }

    #[Route('/Ajout/{id}', name: 'app_ajout_panier')]
    public function add(Montre $montre, Request $request): Response
    {
        $session = $request->getSession();

        $panier = $session->get('panier');

        // Si le panier est vide
        if (!$panier) {
            $panier = [
                $montre->getId() => 1
            ];
        } else {
            // Cas ou panier est non vide

            // Le cas ou j'ai déjà rajouter cette montre dans le panier
            if ($panier[$montre->getId()] ?? false) {
                $panier[$montre->getId()] +=  1;
            } else {
                $panier[$montre->getId()] =  1;
            }
        }

        $session->set('panier', $panier);
        $this->addFlash('success', 'Element à bien été rajouté dans le panier');
        return $this->redirectToRoute('app_montre_show', ['id' => $montre->getId()]);
    }
}
