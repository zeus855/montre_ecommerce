<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Montre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SiteMapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ['_format' => 'xml'])]
    public function index(
        Request $request, 
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ): Response
    {
        $urls = [];

        // Générer les routes statiques
        $urls[] = ['loc' => $urlGenerator->generate('app_contact', [], UrlGeneratorInterface::ABSOLUTE_URL)];
        $urls[] = ['loc' => $urlGenerator->generate('app_cgv', [], UrlGeneratorInterface::ABSOLUTE_URL)];

        $urls[] = ['loc' => $urlGenerator->generate('app_panier', [], UrlGeneratorInterface::ABSOLUTE_URL)];

        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        foreach($categories as $categorie) {
            $urls[] = ['loc' => $urlGenerator->generate('front_categorie', ['id' => $categorie->getId()], UrlGeneratorInterface::ABSOLUTE_URL)];
        }

        $montres = $entityManager->getRepository(Montre::class)->findBy([]);

        foreach($montres as $montre) {
            $urls[] = [
                'loc' => $urlGenerator->generate('app_montre_front', ['slug' => $montre->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'daily',
            ];
        }

        $response = new Response(
            $this->renderView('site_map/index.html.twig', ['urls' => $urls]),
            200
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
