<?php

namespace App\Controller;

use App\Entity\Montre;
use App\Repository\MontreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/montre', name: 'app_montre_')]
class MontreFrontController extends AbstractController
{
    #[Route('/{id}/front', name: 'front')]
    public function montre(Montre $montre): Response
    {
        return $this->render('montre_front/index.html.twig', [
            'montre' => $montre,
        ]);
    }


    // #[Route('/liste', name: 'liste')]
    // public function liste(MontreRepository $montreRepository): Response
    // {
    //     $montres = $montreRepository->findBy([]);
    //     return $this->render('montre_front/liste.html.twig', [
    //         'montres' => $montres,
    //     ]);
    // }
}
