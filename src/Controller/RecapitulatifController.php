<?php

namespace App\Controller;

use App\Constante\CommandeConstante;
use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\Montre;
use App\Entity\MontreCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecapitulatifController extends AbstractController
{
    #[Route('/recapitulatif/success', name: 'app_recapitulatif_success')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = $request->getSession()->get('panier');

        $montres = $entityManager->getRepository(Montre::class)->findBy(['id' => array_keys($panier)]);

        $commande = new Commande();
        $commande->setStatut(CommandeConstante::TERMINER);
        $commande->setUser($this->getUser());
        $entityManager->persist($commande);

        $total = 0;

        foreach ($montres as $montre) {
            $quantite = $panier[$montre->getId()];

            $total += ($quantite * $montre->getPrix());

            $montreCommande = new MontreCommande();
            $montreCommande->setCommande($commande);
            $montreCommande->setMontre($montre);
            $montreCommande->setQuantite($quantite);
            $montreCommande->setUser($this->getUser());

            $entityManager->persist($montreCommande);
        }

        $commande->setTotal($total);

        $adresses = $request->getSession()->get('adresse');

        $livraison = $adresses['livraison'] ?? null;

        if ($livraison) {
            $adresse = $entityManager->getRepository(Adresse::class)->find($livraison);

            $commande->setLivraison($adresse);
        }

        $facturation = $adresses['facturation'] ?? null;

        if ($facturation) {
            $adresse = $entityManager->getRepository(Adresse::class)->find($facturation);

            $commande->setFacturation($adresse);
        }

        $entityManager->flush();

        $request->getSession()->remove('panier');
        $request->getSession()->remove('adresse');

        // $this->addFlash('success', 'Félicitation votre paiement à bien été accepté');

        return $this->render('recapitulatif/index.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/recapitulatif/failed', name: 'app_recapitulatif_failed')]
    public function failed(Request $request, EntityManagerInterface $entityManager): Response
    {
        // $this->addFlash('danger', 'Un problème est survenue lors de votre paiement, merci de reessayer plus tard');

        $panier = $request->getSession()->get('panier');

        $montres = $entityManager->getRepository(Montre::class)->findBy(['id' => array_keys($panier)]);

        $commande = new Commande();
        $commande->setStatut(CommandeConstante::TERMINER);
        $commande->setUser($this->getUser());
        $entityManager->persist($commande);

        $total = 0;

        foreach ($montres as $montre) {
            $quantite = $panier[$montre->getId()];

            $total += ($quantite * $montre->getPrix());

            $montreCommande = new MontreCommande();
            $montreCommande->setCommande($commande);
            $montreCommande->setMontre($montre);
            $montreCommande->setQuantite($quantite);
            $montreCommande->setUser($this->getUser());

            $entityManager->persist($montreCommande);
        }

        $commande->setTotal($total);

        $adresses = $request->getSession()->get('adresse');

        $livraison = $adresses['livraison'] ?? null;

        if ($livraison) {
            $adresse = $entityManager->getRepository(Adresse::class)->find($livraison);

            $commande->setLivraison($adresse);
        }

        $facturation = $adresses['facturation'] ?? null;

        if ($facturation) {
            $adresse = $entityManager->getRepository(Adresse::class)->find($facturation);

            $commande->setFacturation($adresse);
        }

        return $this->render('recapitulatif/failed.html.twig', [
            'commande' => $commande
        ]);
    }
}
