<?php

namespace App\Controller;

use App\Entity\Montre;
use App\Repository\MontreRepository;
use App\Repository\MontreCommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

// on a rajouter pour la validation panier
    #[Route('/panier/validation', name: 'app_panier_validation')]
    #[IsGranted('ROLE_USER')]
    public function validation(Request $request, MontreRepository $montreRepository): Response
    {
        $user= $this->getUser();
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
        $this->addFlash('success', 'Le produit à bien été rajouté dans le panier');
        return $this->redirectToRoute('app_montre_show', ['id' => $montre->getId()]);
    }

    // permet d'afficher les elements de paniers
    #[Route('/update/{id}/element/{valeur}', name: 'app_update_panier')]
    public function update(Montre $montre,int $valeur, Request $request): Response
    {
        $session = $request->getSession();

        $panier = $session->get('panier');
        $montreid = $montre->getId();
        if ($panier[$montreid] ?? false) {
            $panier[$montreid] = $valeur;
         }
         
         $session->set('panier', $panier);
         
         $this->addFlash('success', 'Votre panier à été mis à jour');
         
         return $this->redirectToRoute('app_panier');
         
    }



        
}
