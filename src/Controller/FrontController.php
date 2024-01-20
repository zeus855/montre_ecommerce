<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\SearchType;
use App\Model\Search;
use App\Repository\HomePageRepository;
use App\Repository\MontreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(HomePageRepository $homePageRepository): Response
    {
        // Commande qui permet de vider le panier
        // $request->getSession()->remove('panier');

        return $this->render('front/index.html.twig', [
            'home_page' => $homePageRepository->findOneBy([], ['id' => 'DESC'], 1),
        ]);
    }

    #[Route('/', name: 'front')]
    public function liste(Request $request, MontreRepository $montreRepository): Response
    {
        $term = $request->query->get('search');

        $montres = $montreRepository->searchByTerm($term);

        return $this->render('montre_front/liste.html.twig', [
            'montres' => $montres,
        ]);
    }

    #[Route('/advanced', name: 'search_advanced')]
    public function advanced(Request $request, MontreRepository $montreRepository): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $montres = $montreRepository->searchByCriteria($search);
        } else {
            $montres = $montreRepository->findAll();
        }

        return $this->render('montre_front/advanced_search.html.twig', [
            'montres' => $montres,
            'form' => $form->createView()
        ]);
    }


    #[Route('/{id}/categorie', name: 'front_categorie')]
    public function categorie(MontreRepository $montreRepository, Categorie $categorie): Response
    {
        $montres = $montreRepository->findBy(['categorie' => $categorie]);
        return $this->render('montre_front/liste.html.twig', [
            'montres' => $montres,
            'categorie' => $categorie
        ]);
    }
}
